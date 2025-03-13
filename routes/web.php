<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\DBAController;
use App\Http\Controllers\CitaController;

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

//------------- RUTAS PACIENTE ---------------//

Route::middleware(['rol:Paciente'])->group(function () {
    Route::get('/paciente/dashboard', [PacienteController::class, 'dashboard'])->name('paciente.dashboard');
});

Route::middleware(['rol:Paciente'])->group(function () {
    Route::get('/paciente/historial', [PacienteController::class, 'historial'])->name('paciente.historial');
});

// Route::middleware(['rol:Paciente'])->group(function () {
//     Route::get('/paciente/reserva', [PacienteController::class, 'reserva'])->name('paciente.reserva');
// });

//------------- RUTAS MEDICO ---------------//

Route::middleware(['rol:Medico'])->group(function () {
    Route::get('/medico/dashboard', [MedicoController::class, 'dashboard'])->name('medico.dashboard');
});

Route::middleware(['rol:Medico'])->group(function () {
    Route::get('/medico/historial', [MedicoController::class, 'historial'])->name('medico.historial');
});

Route::middleware(['rol:Medico'])->group(function () {
    Route::get('/medico/reserva', [MedicoController::class, 'reserva'])->name('medico.reserva');
});

//------------- RUTAS DBA ---------------//

Route::middleware(['rol:DBA'])->group(function () {
    Route::get('/dba/dashboard', [DBAController::class, 'dashboard'])->name('dba.dashboard');
});

//-------------- RUTAS PACIENTE.RESERVA -------------//
Route::middleware(['rol:Paciente'])->group(function () {
    Route::get('/paciente/reservar', [CitaController::class, 'mostrarFormularioReserva'])->name('paciente.reservar');
});

Route::get('/medicos-por-especialidad/{especialidad_id}', [CitaController::class, 'obtenerMedicosPorEspecialidad'])->name('medicos.por.especialidad');

Route::get('/horas-disponibles/{medico_id}/{fecha}', [CitaController::class, 'obtenerHorasDisponibles'])->name('horas.disponibles');

Route::post('/procesar-reserva', [CitaController::class, 'store'])->name('procesar.reserva');