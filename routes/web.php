<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

// Redirigir / al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Auth::routes();

// Página después de iniciar sesión
Route::get('/inicio', [App\Http\Controllers\InicioController::class, 'index'])->name('inicio');



// Página de monitoreo
Route::get('/monitoreo', function () {
    return view('auth.monitoreo');
})->name('monitoreo');

//Página de incidentes :)
Route::get('/incidentes', function (){
    return view('auth.incidentes');
})->name('incidentes');

//Página de contabilidad :)
Route::get('/contabilidad', function (){
    return view('auth.contabilidad');
})->name('contabilidad');

//Página de contabilidad :)
Route::get('/mantenimiento', function (){
    return view('auth.mantenimiento');
})->name('mantenimiento');

//Página de pasajero :)
Route::get('/pasajerof', function (){
    return view('auth.pasajerof');
})->name('pasajerof');



