<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// RUTA INTERMEDIARIA PARA MODIFICAR EL HOME EN ROUTE SERVICE PROVIDER
Route::get('/redirect-dashboard', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect('/login');
    }

    return match ($user->rol) {
        'DBA' => redirect()->route('dba.dashboard'),
        'Medico' => redirect()->route('medico.dashboard'),
        'Paciente' => redirect()->route('paciente.dashboard'),
        default => redirect('/'),
    };
})->middleware('auth')->name('redirect.dashboard');

//------------- RUTAS POR DEFECTO ---------------//

Route::get('/', function () {
    return redirect()->route('login');
});

//------------- RUTAS AUTH (BREEZE) ---------------//

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Ruta para mostrar imagenes de prescripciones
Route::get('/prescripciones/{filename}', function ($filename) {
    $path = storage_path('app/prescripciones/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    if (!auth()->check()) {
        abort(403, 'No tienes permiso para ver esta imagen.');
    }

    $file = file_get_contents($path);
    $type = mime_content_type($path);

    return Response::make($file, 200)->header('Content-Type', $type);
})->name('prescripciones.ver');

// ============= RUTAS DBA ============= //
Route::middleware(['auth', 'rol:DBA'])->group(function () {
    // Dashboard
    Route::get('/dba/dashboard', [DashboardController::class, 'dbaDashboard'])->name('dba.dashboard');
    
    // Usuarios
    Route::prefix('/dba/usuarios')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('dba.usuarios.resumen');
        Route::get('/crear', [UserController::class, 'create'])->name('dba.usuarios.create');
        Route::get('/{usuario}', [UserController::class, 'show'])->name('dba.usuarios.show');
        Route::post('/crear-usuario', [UserController::class, 'store'])->name('dba.usuarios.store');
        Route::get('/{usuario}/editar', [UserController::class, 'edit'])->name('dba.usuarios.edit');
        Route::put('/update-usuario/{usuario}', [UserController::class, 'update'])->name('dba.usuarios.update');
    });
    
    // Backups
    Route::prefix('/dba/backups')->group(function () {
        Route::get('/', [BackupController::class, 'listBackups'])->name('backups.index');
        Route::post('/create', [BackupController::class, 'createBackup'])->name('backups.create');
        Route::get('/download/{file}', [BackupController::class, 'downloadBackup'])->name('backups.download');
        Route::delete('/delete/{file}', [BackupController::class, 'deleteBackup'])->name('backups.delete');
    });
});

// ============= RUTAS PACIENTE ============= //
Route::middleware(['auth', 'rol:Paciente'])->group(function () {
    // Dashboard
    Route::get('/paciente/dashboard', [DashboardController::class, 'pacienteDashboard'])->name('paciente.dashboard');
    
    // Perfil
    Route::get('/paciente/perfil', [PacienteController::class, 'mostrarInformacion'])->name('paciente.informacion');
    
    // Citas
    Route::prefix('/paciente/citas')->group(function () {
        Route::get('/reservar', [CitaController::class, 'mostrarFormularioReservaPaciente'])->name('paciente.citas.reservar');
        Route::get('/', [CitaController::class, 'mostrarResumenPaciente'])->name('paciente.citas.resumen');
        Route::get('/{id}', [CitaController::class, 'mostrarDetalles'])->name('paciente.citas.detalles');
        Route::put('/{id}/cancelar', [CitaController::class, 'cancelarCita'])->name('citas.cancelar');
    });
    
    // Procesamiento de citas
    Route::post('/procesar-reserva', [CitaController::class, 'store'])->name('procesar.reserva');
});

// Rutas de API para paciente (sin auth para que funcionen los AJAX)
Route::middleware(['auth', 'rol:Paciente'])->group(function () {
    Route::get('/medicos-por-especialidad/{especialidad_id}', [CitaController::class, 'obtenerMedicosPorEspecialidad'])->name('medicos.por.especialidad');
    Route::get('/horas-disponibles/{medico_id}/{fecha}', [CitaController::class, 'obtenerHorasDisponibles'])->name('horas.disponibles');
});

// ============= RUTAS MÉDICO ============= //
Route::middleware(['auth', 'rol:Medico'])->group(function () {
    // Dashboard
    Route::get('/medico/dashboard', [DashboardController::class, 'medicoDashboard'])->name('medico.dashboard');
    
    // Perfil
    Route::get('/medico/perfil', [MedicoController::class, 'mostrarInformacion'])->name('medico.informacion');
    
    // Pacientes
    Route::prefix('/medico/pacientes')->group(function () {
        Route::get('/', [MedicoController::class, 'pacientes'])->name('medico.pacientes.resumen');
        Route::get('/{paciente}', [MedicoController::class, 'showPaciente'])->name('medico.pacientes.detalles');
    });
    
    // Citas
    Route::prefix('/medico/citas')->group(function () {
        Route::get('/reservar/{dni?}', [CitaController::class, 'mostrarFormularioReservaMedico'])->name('medico.citas.reservar');
        Route::get('/', [CitaController::class, 'mostrarResumenMedico'])->name('medico.citas.resumen');
        Route::get('/{id}', [CitaController::class, 'mostrarDetalles'])->name('medico.citas.detalles');
        Route::put('/{id}/confirmar', [CitaController::class, 'confirmarCita'])->name('medico.citas.confirmar');
        Route::put('/{id}/rechazar', [CitaController::class, 'rechazarCita'])->name('medico.citas.rechazar');
        Route::put('/{id}/completar', [CitaController::class, 'completarCita'])->name('medico.citas.completar');
    });
    
    // Procesamiento de citas
    Route::post('/medico/procesar-reserva', [CitaController::class, 'procesarReservaMedico'])->name('medico.procesar.reserva');
});

// Rutas API
Route::middleware(['auth', 'rol:Medico'])->group(function () {
    Route::get('/buscar-paciente-por-dni/{dni}', [CitaController::class, 'buscarPacientePorDni'])->name('buscar.paciente.por.dni');
    Route::get('/horas-disponibles-medico/{dni}/{fecha}', [CitaController::class, 'obtenerHorasDisponiblesMedico'])->name('horas.disponibles.medico');
});



