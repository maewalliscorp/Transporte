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

//Página de asignación
Route::get('/asignacion', function () {
    return view('inicio');
})->name('asignacion');

// Página de monitoreo
Route::get('/monitoreo', function () {
    return view('auth.monitoreo');
})->name('monitoreo');

//Página de incidentes :)
Route::get('/incidentes', function (){
    return view('auth.incidentes');
})->name('incidentes');





