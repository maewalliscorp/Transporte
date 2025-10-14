<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\HorarioController;

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

//Página de contabilidad :)
Route::get('/contabilidad', [App\Http\Controllers\ContabilidadController::class, 'index'])->name('contabilidad');

//Página de contabilidad :)
Route::get('/mantenimiento', function (){
    return view('auth.mantenimiento');
})->name('mantenimiento');

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
});

Route::get('/agregar/horarios', [HorarioController::class, 'index'])->name('agregar.horarios');


