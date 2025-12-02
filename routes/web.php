<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\AsignarController;
use App\Http\Controllers\IncidentesController;
use App\Http\Controllers\FinanzasController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\TincidenteController;
use App\Http\Controllers\MalertasController;
use App\Http\Controllers\MhistorialController;
use App\Http\Controllers\MprogramadoController;
use App\Http\Controllers\MrealizadoController;
use App\Http\Controllers\PhistorialController;
use App\Http\Controllers\PquejasugerenciaController;
use App\Http\Controllers\PregistroController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Redirigir / al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Auth::routes();

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    // ========== RUTA DE BIENVENIDA - TODOS LOS USUARIOS ==========
    Route::get('/inicio', [InicioController::class, 'index'])->name('inicio');

    // ========== RUTAS PARA ADMINISTRADOR ==========
    Route::middleware('role:administrador')->group(function () {
        Route::post('/asignar/asignar', [AsignarController::class, 'asignar'])->name('asignar.store');

        // Monitoreo
        Route::get('/monitoreo', function () {
            return view('auth.monitoreo');
        })->name('monitoreo');

        // Menú Agregar
        Route::prefix('agregar')->group(function () {
            Route::get('/unidades', [UnidadController::class, 'index'])->name('agregar.unidades');
            Route::get('/operadores', [OperadorController::class, 'index'])->name('agregar.operadores');
            Route::get('/rutas', [RutaController::class, 'index'])->name('agregar.rutas');
            Route::get('/horarios', [HorarioController::class, 'index'])->name('agregar.horarios');
            Route::get('/tincidente', [TincidenteController::class, 'index'])->name('agregar.tincidente');
        });

        // CRUD Unidades
        Route::get('/unidades', [UnidadController::class, 'index'])->name('unidades.index');
        Route::post('/unidades', [UnidadController::class, 'store'])->name('unidades.store');
        Route::get('/unidades/{id}', [UnidadController::class, 'show'])->name('unidades.show');
        Route::put('/unidades/{id}', [UnidadController::class, 'update'])->name('unidades.update');
        Route::delete('/unidades/{id}', [UnidadController::class, 'destroy'])->name('unidades.destroy');

        // CRUD Operadores
        Route::get('/operadores', [OperadorController::class, 'index'])->name('operadores.index');
        Route::post('/operadores', [OperadorController::class, 'store'])->name('operadores.store');
        Route::get('/operadores/{id}', [OperadorController::class, 'show'])->name('operadores.show');
        Route::put('/operadores/{id}', [OperadorController::class, 'update'])->name('operadores.update');
        Route::delete('/operadores/{id}', [OperadorController::class, 'destroy'])->name('operadores.destroy');

        // CRUD Rutas
        Route::get('/rutas', [RutaController::class, 'index'])->name('rutas.index');
        Route::post('/rutas', [RutaController::class, 'store'])->name('rutas.store');
        Route::get('/rutas/{id}', [RutaController::class, 'show'])->name('rutas.show');
        Route::put('/rutas/{id}', [RutaController::class, 'update'])->name('rutas.update');
        Route::delete('/rutas/{id}', [RutaController::class, 'destroy'])->name('rutas.destroy');

        // CRUD Horarios
        Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios.index');
        Route::post('/horarios', [HorarioController::class, 'store'])->name('horarios.store');
        Route::get('/horarios/{id}', [HorarioController::class, 'show'])->name('horarios.show');
        Route::put('/horarios/{id}', [HorarioController::class, 'update'])->name('horarios.update');
        Route::delete('/horarios/{id}', [HorarioController::class, 'destroy'])->name('horarios.destroy');

        // CRUD Tincidente
        Route::get('/tincidente', [TincidenteController::class, 'index'])->name('tincidente.index');
        Route::post('/tincidente', [TincidenteController::class, 'store'])->name('tincidente.store');
        Route::get('/tincidente/{id}', [TincidenteController::class, 'show'])->name('tincidente.show');
        Route::put('/tincidente/{id}', [TincidenteController::class, 'update'])->name('tincidente.update');
        Route::delete('/tincidente/{id}', [TincidenteController::class, 'destroy'])->name('tincidente.destroy');

        // Mantenimiento
        Route::post('/mantenimiento/programado', [MprogramadoController::class, 'store']);
        Route::get('/mantenimiento/programado/{id}', [MprogramadoController::class, 'show']);
        Route::put('/mantenimiento/programado/{id}', [MprogramadoController::class, 'update']);
        Route::delete('/mantenimiento/programado/{id}', [MprogramadoController::class, 'destroy']);

        Route::get('/mantenimiento/m-historial', [MhistorialController::class, 'index'])->name('mantenimiento.m-historial');

    });

    // ========== RUTAS PARA OPERADOR ==========
    Route::middleware('role:operador')->group(function () {
        Route::post('/incidentes', [IncidentesController::class, 'store'])->name('incidentes.store');
        Route::get('/incidentes/{id}', [IncidentesController::class, 'show'])->name('incidentes.show');

    });

    // ========== RUTAS PARA SUPERVISOR ==========
    Route::middleware('role:supervisor')->group(function () {
        // Resolver incidentes
        Route::put('/incidentes/{id}', [IncidentesController::class, 'update'])->name('incidentes.update');
        Route::post('/incidentes/solucionar', [IncidentesController::class, 'solucionar'])->name('incidentes.solucionar');
        Route::post('/incidentes/{id}/solucionar', [IncidentesController::class, 'solucionar'])->name('incidentes.solucionar');
    });

    // ========== RUTAS PARA PASAJERO ==========
    Route::middleware('role:pasajero')->group(function () {
        // Historial de Viajes
        Route::get('/pasajero/p-historial', [PhistorialController::class, 'index'])->name('pasajero.p-historial');
        Route::get('/pasajero/historial/data', [PhistorialController::class, 'getHistorial'])->name('pasajero.historial.data');

        // Quejas y Sugerencias
        Route::get('/pasajero/p-quejas', [PquejasugerenciaController::class, 'index'])->name('pasajero.p-queja-sugerencia');
        Route::post('/pasajero/quejas', [PquejasugerenciaController::class, 'store'])->name('pasajero.quejas.store');
    });

    // ========== RUTAS COMPARTIDAS ==========

    // Asignaciones: Administrador y Supervisor (solo vista)
    Route::middleware('role:administrador,supervisor')->group(function () {
        Route::get('/asignar', [AsignarController::class, 'index'])->name('asignar');
    });

    // Finanzas: Administrador y Contador
    Route::middleware('role:administrador,contador')->group(function () {
        Route::get('/finanzas', [FinanzasController::class, 'index'])->name('finanzas');
        Route::post('/finanzas/ingresos', [FinanzasController::class, 'storeIngreso'])->name('finanzas.ingresos.store');
        Route::put('/finanzas/ingresos/{id}', [FinanzasController::class, 'updateIngreso'])->name('finanzas.ingresos.update');
        Route::delete('/finanzas/ingresos/{id}', [FinanzasController::class, 'destroyIngreso'])->name('finanzas.ingresos.destroy');
        Route::post('/finanzas/egresos', [FinanzasController::class, 'storeEgreso'])->name('finanzas.egresos.store');
        Route::put('/finanzas/egresos/{id}', [FinanzasController::class, 'updateEgreso'])->name('finanzas.egresos.update');
        Route::delete('/finanzas/egresos/{id}', [FinanzasController::class, 'destroyEgreso'])->name('finanzas.egresos.destroy');
        Route::post('/finanzas/tarifas', [FinanzasController::class, 'storeTarifa'])->name('finanzas.tarifas.store');
        Route::put('/finanzas/tarifas/{id}', [FinanzasController::class, 'updateTarifa'])->name('finanzas.tarifas.update');
        Route::delete('/finanzas/tarifas/{id}', [FinanzasController::class, 'destroyTarifa'])->name('finanzas.tarifas.destroy');
        Route::post('/finanzas/conciliaciones', [FinanzasController::class, 'storeConciliacion'])->name('finanzas.conciliaciones.store');
    });

    // Registro de Pasajeros: Administrador y Pasajero
    Route::middleware('role:administrador,pasajero')->group(function () {
        Route::get('/pasajero/p-registro', [PregistroController::class, 'index'])->name('pasajero.p-registro');
        Route::post('/pasajero/registro', [PregistroController::class, 'store'])->name('pasajero.registro.store');
    });

    // Mantenimiento programado: Administrador, Operador y Supervisor
    Route::middleware('role:administrador,operador,supervisor')->group(function () {
        Route::get('/mantenimiento/m-programado', [MprogramadoController::class, 'index'])->name('mantenimiento.m-programado');
    });

    // Mantenimiento realizado y alertas: Administrador y Supervisor
    Route::middleware('role:administrador,supervisor')->group(function () {
        Route::get('/mantenimiento/m-realizado', [MrealizadoController::class, 'index'])->name('mantenimiento.m-realizado');
        Route::post('/mantenimiento/realizado', [MrealizadoController::class, 'store']);
        Route::get('/mantenimiento/realizado/{id}', [MrealizadoController::class, 'show']);
        Route::put('/mantenimiento/realizado/{id}', [MrealizadoController::class, 'update']);
        Route::delete('/mantenimiento/realizado/{id}', [MrealizadoController::class, 'destroy']);

        Route::get('/mantenimiento/m-alertas', [MalertasController::class, 'index'])->name('mantenimiento.m-alertas');
        Route::post('/mantenimiento/alerta', [MalertasController::class, 'store']);
        Route::get('/mantenimiento/alerta/{id}', [MalertasController::class, 'show']);
        Route::put('/mantenimiento/alerta/{id}', [MalertasController::class, 'update']);
        Route::delete('/mantenimiento/alerta/{id}', [MalertasController::class, 'destroy']);
    });

    // Consulta de incidentes: Operador y Supervisor
    Route::middleware('role:operador,supervisor')->group(function () {
        Route::get('/incidentes', [IncidentesController::class, 'index'])->name('incidentes');
    });
});

// Rutas de autenticación (password reset)
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
