<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\DBAController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\UserController;
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

//------------- 1. RUTAS PACIENTE ---------------//

Route::middleware(['rol:Paciente'])->group(function () {
    Route::get('/paciente/dashboard', [PacienteController::class, 'dashboard'])->name('paciente.dashboard');
});

Route::get('/paciente/informacion', [PacienteController::class, 'mostrarInformacion'])->name('paciente.informacion');

//--------------- 1.1 RESERVAR ---------------------//
Route::middleware(['rol:Paciente'])->group(function () {
    Route::get('/paciente/citas/reservar', [CitaController::class, 'mostrarFormularioReservaPaciente'])->name('paciente.citas.reservar');
});

Route::get('/medicos-por-especialidad/{especialidad_id}', [CitaController::class, 'obtenerMedicosPorEspecialidad'])->name('medicos.por.especialidad');

Route::get('/horas-disponibles/{medico_id}/{fecha}', [CitaController::class, 'obtenerHorasDisponibles'])->name('horas.disponibles');

Route::post('/procesar-reserva', [CitaController::class, 'store'])->name('procesar.reserva');

//----------------1.2 RESUMEN--------------------//
Route::get('/paciente/citas', [CitaController::class, 'mostrarResumenPaciente'])->name('paciente.citas.resumen');

//----------------1.3 DETALLES-------------------//
Route::get('paciente/citas/{id}', [CitaController::class, 'mostrarDetalles'])->name('paciente.citas.detalles');

Route::middleware(['auth', 'rol:Paciente'])->group(function () {
    Route::put('/citas/{id}/cancelar', [CitaController::class, 'cancelarCita'])->name('citas.cancelar');
});

// 2. ------------- RUTAS MEDICO ---------------//

Route::middleware(['rol:Medico'])->group(function () {
    Route::get('/medico/dashboard', [MedicoController::class, 'dashboard'])->name('medico.dashboard');
});

Route::get('/medico/pacientes', [MedicoController::class, 'pacientes'])->name('medico.pacientes.resumen');
Route::get('/medico/pacientes/{paciente}', [MedicoController::class, 'showPaciente'])->name('medico.pacientes.detalles');

// -----------------2.1 RESERVAR --------------//
Route::get('/medico/citas/reservar', [CitaController::class, 'mostrarFormularioReservaMedico'])->name('medico.citas.reservar');
Route::get('/buscar-paciente-por-dni/{dni}', [CitaController::class, 'buscarPacientePorDni'])->name('buscar.paciente.por.dni');
Route::get('/horas-disponibles-medico/{dni}/{fecha}', [CitaController::class, 'obtenerHorasDisponiblesMedico'])->name('horas.disponibles.medico');
Route::post('/medico/procesar-reserva', [CitaController::class, 'procesarReservaMedico'])->name('medico.procesar.reserva');

// --------------- 2.2 RESUMEN ----------------//
Route::get('/medico/citas', [CitaController::class, 'mostrarResumenMedico'])->name('medico.citas.resumen');


// ----------------2.3 DETALLES ---------------//
Route::get('/medico/citas/{id}', [CitaController::class, 'mostrarDetalles'])->name('medico.citas.detalles');

Route::put('/medico/citas/{id}/confirmar', [CitaController::class, 'confirmarCita'])->name('medico.citas.confirmar');
Route::put('/medico/citas/{id}/rechazar', [CitaController::class, 'rechazarCita'])->name('medico.citas.rechazar');
Route::put('/medico/citas/{id}/completar', [CitaController::class, 'completarCita'])->name('medico.citas.completar');

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

//------------- RUTAS DBA ---------------//

Route::middleware(['rol:DBA'])->group(function () {
    Route::get('/dba/dashboard', [DBAController::class, 'dashboard'])->name('dba.dashboard');
});

// ------------- 1.1 USUARIOS -----------------//
Route::get('/dba/usuarios', [UserController::class, 'index'])->name('dba.usuarios.resumen');
Route::get('/dba/usuarios/create', [UserController::class, 'create'])->name('dba.usuarios.create');
Route::get('/dba/usuarios/{usuario}', [UserController::class, 'show'])->name('dba.usuarios.show');
Route::post('/crear-usuario', [UserController::class, 'store'])->name('dba.usuarios.store');
Route::get('/dba/usuarios/{usuario}/editar', [UserController::class, 'edit'])->name('dba.usuarios.edit');
Route::put('/update-usuario/{usuario}', [UserController::class, 'update'])->name('dba.usuarios.update');



