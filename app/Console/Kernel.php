<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Cita;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            Cita::where('estado', 'confirmada')
                ->whereDate('fecha', '<', now()->toDateString())
                ->orWhere(function($query) {
                    $query->whereDate('fecha', now()->toDateString())
                          ->whereTime('hora_fin', '<', now()->toTimeString());
                })
                ->update([
                    'estado' => 'completada',
                    'notas_medico' => DB::raw('COALESCE(CONCAT(notas_medico, "\n[Cita completada automáticamente por sistema]"), "[Cita completada automáticamente por sistema]")')
                ]);
        })->dailyAt('23:59');
    
        $schedule->call(function () {
            Cita::where('estado', 'pendiente')
                ->whereDate('fecha', now()->addDay()->toDateString())
                ->update([
                    'estado' => 'cancelada',
                    'notas_medico' => DB::raw('COALESCE(CONCAT(notas_medico, "\n[Cita cancelada automáticamente por falta de confirmación]"), "[Cita cancelada automáticamente por falta de confirmación]")')
                ]);
        })->dailyAt('23:59');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
