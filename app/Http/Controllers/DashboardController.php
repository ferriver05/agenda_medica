<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Medico;
use App\Models\Especialidad;
use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dbaDashboard() {
        $usersCount = User::count();
        
        $usersByRole = User::select('rol', DB::raw('count(*) as total'))
                            ->groupBy('rol')
                            ->get()
                            ->pluck('total', 'rol');
    
        $appointmentsStatus = Cita::select('estado', DB::raw('count(*) as total'))
                                  ->groupBy('estado')
                                  ->get();
        
        $topDoctors = Medico::withCount('citas')
                            ->orderByDesc('citas_count')
                            ->limit(5)
                            ->get();
    
        $topSpecialties = Especialidad::withCount('citas')
                                      ->orderByDesc('citas_count')
                                      ->limit(3)
                                      ->get();
    
        return view('dba.dashboard', compact(
            'usersCount', 
            'usersByRole',
            'appointmentsStatus',
            'topDoctors',
            'topSpecialties'
        ));
    }

    public function medicoDashboard()
    {
        $doctor = Medico::where('user_id', auth()->id())->firstOrFail();
        
        $confirmedAppointments = Cita::where('medico_id', $doctor->id)
            ->where('estado', 'confirmada')
            ->count();
            
        $pendingAppointments = Cita::where('medico_id', $doctor->id)
            ->where('estado', 'pendiente')
            ->count();

        return view('medico.dashboard', [
            'todayAppointments' => Cita::where('medico_id', $doctor->id)
                ->whereDate('fecha', today())
                ->count(),
                
            'pendingAppointments' => $pendingAppointments,
            'confirmedAppointments' => $confirmedAppointments,
                
            'monthlyCompleted' => Cita::where('medico_id', $doctor->id)
                ->where('estado', 'completada')
                ->whereMonth('fecha', now()->month)
                ->count(),
                
            'monthlyCancelled' => Cita::where('medico_id', $doctor->id)
                ->where('estado', 'cancelada')
                ->whereMonth('fecha', now()->month)
                ->count(),
                
            'todayConfirmedAppointments' => Cita::with('paciente.user')
                ->where('medico_id', $doctor->id)
                ->where('estado', 'confirmada')
                ->whereDate('fecha', today())
                ->orderBy('hora_inicio')
                ->get(),
                
            'recentCompletedPatients' => Paciente::with(['user'])
                ->whereHas('citas', function($q) use ($doctor) {
                    $q->where('medico_id', $doctor->id)
                      ->where('estado', 'completada');
                })
                ->with(['citas' => function($q) {
                    $q->where('estado', 'completada')
                      ->latest()
                      ->limit(1);
                }])
                ->latest()
                ->limit(8)
                ->get()
                ->each(function($patient) {
                    $patient->last_completed = $patient->citas->first()->fecha ?? null;
                }),
                
            'monthlyStats' => Cita::where('medico_id', $doctor->id)
                ->whereBetween('fecha', [now()->startOfMonth(), now()->endOfMonth()])
                ->selectRaw('DAY(fecha) as day')
                ->selectRaw('SUM(CASE WHEN estado = "completada" THEN 1 ELSE 0 END) as completed')
                ->selectRaw('SUM(CASE WHEN estado = "cancelada" THEN 1 ELSE 0 END) as cancelled')
                ->groupBy('day')
                ->orderBy('day')
                ->get()
        ]);
    }

    public function pacienteDashboard()
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        // 1. Próxima cita confirmada
        $nextAppointment = Cita::with(['medico.user', 'especialidad'])
            ->where('paciente_id', $paciente->id)
            ->whereIn('estado', ['confirmada', 'pendiente'])
            ->where('fecha', '>=', now())
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->first();

        // 2. Citas completadas (total)
        $completedAppointments = Cita::where('paciente_id', $paciente->id)
            ->where('estado', 'completada')
            ->count();

        // 3. Próximas citas (confirmadas/pendientes)
        $upcomingAppointments = Cita::with(['medico.user', 'especialidad'])
            ->where('paciente_id', $paciente->id)
            ->whereIn('estado', ['confirmada', 'pendiente'])
            ->where('fecha', '>=', now())
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->limit(5)
            ->get();

        // 4. Historial de citas (completadas recientes)
        $appointmentHistory = Cita::with(['medico.user', 'especialidad'])
            ->where('paciente_id', $paciente->id)
            ->where('estado', 'completada')
            ->orderBy('fecha', 'desc')
            ->limit(5)
            ->get();

        // 5. Datos para gráfico de últimos 6 meses
        $appointmentsLastMonths = Cita::where('paciente_id', $paciente->id)
            ->where('estado', 'completada')
            ->where('fecha', '>=', now()->subMonths(6))
            ->selectRaw("DATE_FORMAT(fecha, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::createFromFormat('Y-m', $item->month)->format('M Y'),
                    'count' => $item->count
                ];
            });

        // Si no hay datos para algún mes, completamos con ceros
        $lastSixMonths = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('M Y');
            $data = $appointmentsLastMonths->firstWhere('month', $month);
            $lastSixMonths->push([
                'month' => $month,
                'count' => $data['count'] ?? 0
            ]);
        }

        return view('paciente.dashboard', [
            'nextAppointment' => $nextAppointment,
            'completedAppointments' => $completedAppointments,
            'upcomingAppointments' => $upcomingAppointments,
            'appointmentHistory' => $appointmentHistory,
            'appointmentsLastMonths' => $lastSixMonths,
        ]);
    }
}
