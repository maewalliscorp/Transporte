<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\IncidentesController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\FinanzasController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\TincidenteController;

// Redirigir / al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Auth::routes();

// Página después de iniciar sesión
Route::get('/inicio', [App\Http\Controllers\InicioController::class, 'index'])->name('inicio');

// Página principal después de login
Route::get('/inicio', [InicioController::class, 'index'])->name('inicio');

// Página de monitoreo
Route::get('/monitoreo', function () {
    return view('auth.monitoreo');
})->name('monitoreo');

//Página de incidentes :)
Route::get('/incidentes', function (){
    return view('auth.incidentes');
})->name('incidentes');

// Rutas para Incidentes
Route::get('/incidentes', [App\Http\Controllers\IncidentesController::class, 'index'])->name('incidentes');

//Página de contabilidad :)
Route::get('/finanzas', [FinanzasController::class, 'index'])->name('finanzas');

//Página de mantenimiento :)
Route::get('/mantenimiento', function (){
    return view('auth.mantenimiento');
})->name('mantenimiento');

Route::get('/mantenimiento', [MantenimientoController::class, 'index'])->name('mantenimiento');

//Página de pasajero :)
Route::get('/pasajerof', function (){
    return view('auth.pasajerof');
})->name('pasajerof');

// Ruta para guardar la asignación
Route::post('/asignar', [InicioController::class, 'asignar'])->name('asignar');

Route::post('/gestionar-registro', [InicioController::class, 'gestionarRegistro'])->name('gestionar.registro');

// Rutas para el menú "Agregar"
Route::prefix('agregar')->group(function () {
    Route::get('/unidades', [UnidadController::class, 'index'])->name('agregar.unidades');
    Route::get('/operadores', [OperadorController::class, 'index'])->name('agregar.operadores');
    Route::get('/rutas', [RutaController::class, 'index'])->name('agregar.rutas');
    Route::get('/horarios', [HorarioController::class, 'index'])->name('agregar.horarios');
    Route::get('/tincidente', [TincidenteController::class, 'index'])->name('agregar.tincidente');
});

// Rutas para Horarios
Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios.index');
Route::post('/horarios', [HorarioController::class, 'store'])->name('horarios.store');
Route::get('/horarios/{id}', [HorarioController::class, 'show'])->name('horarios.show');
Route::put('/horarios/{id}', [HorarioController::class, 'update'])->name('horarios.update');
Route::delete('/horarios/{id}', [HorarioController::class, 'destroy'])->name('horarios.destroy');

// Rutas para Unidades
Route::get('/unidades', [UnidadController::class, 'index'])->name('unidades.index');
Route::post('/unidades', [UnidadController::class, 'store'])->name('unidades.store');
Route::get('/unidades/{id}', [UnidadController::class, 'show'])->name('unidades.show');
Route::put('/unidades/{id}', [UnidadController::class, 'update'])->name('unidades.update');
Route::delete('/unidades/{id}', [UnidadController::class, 'destroy'])->name('unidades.destroy');

// Rutas para Rutas
Route::get('/rutas', [RutaController::class, 'index'])->name('rutas.index');
Route::post('/rutas', [RutaController::class, 'store'])->name('rutas.store');
Route::get('/rutas/{id}', [RutaController::class, 'show'])->name('rutas.show');
Route::put('/rutas/{id}', [RutaController::class, 'update'])->name('rutas.update');
Route::delete('/rutas/{id}', [RutaController::class, 'destroy'])->name('rutas.destroy');

// Rutas para Operadores
Route::get('/operadores', [OperadorController::class, 'index'])->name('operadores.index');
Route::post('/operadores', [OperadorController::class, 'store'])->name('operadores.store');
Route::get('/operadores/{id}', [OperadorController::class, 'show'])->name('operadores.show');
Route::put('/operadores/{id}', [OperadorController::class, 'update'])->name('operadores.update');
Route::delete('/operadores/{id}', [OperadorController::class, 'destroy'])->name('operadores.destroy');
