    <?php

    use Inertia\Inertia;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;
    use App\Http\Controllers\InicioController;
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

    // Redirigir / al login
    Route::get('/', function () {
        return redirect()->route('login');
    });

    // Rutas de autenticación
    Auth::routes();

    // Página después de iniciar sesión
    Route::get('/inicio', [App\Http\Controllers\InicioController::class, 'index'])->name('inicio');
    Route::post('/asignar', [App\Http\Controllers\InicioController::class, 'asignar'])->name('asignar');

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

    Route::post('/incidentes', [IncidentesController::class, 'store'])->name('incidentes.store');
    Route::post('/incidentes/solucionar', [IncidentesController::class, 'solucionar'])->name('incidentes.solucionar');

    //Página de contabilidad :)
    Route::get('/finanzas', [FinanzasController::class, 'index'])->name('finanzas');

    // Rutas para Finanzas - Ingresos
    Route::post('/finanzas/ingresos', [FinanzasController::class, 'storeIngreso'])->name('finanzas.ingresos.store');
    Route::put('/finanzas/ingresos/{id}', [FinanzasController::class, 'updateIngreso'])->name('finanzas.ingresos.update');
    Route::delete('/finanzas/ingresos/{id}', [FinanzasController::class, 'destroyIngreso'])->name('finanzas.ingresos.destroy');

    // Rutas para Finanzas - Egresos
    Route::post('/finanzas/egresos', [FinanzasController::class, 'storeEgreso'])->name('finanzas.egresos.store');
    Route::put('/finanzas/egresos/{id}', [FinanzasController::class, 'updateEgreso'])->name('finanzas.egresos.update');
    Route::delete('/finanzas/egresos/{id}', [FinanzasController::class, 'destroyEgreso'])->name('finanzas.egresos.destroy');

    // Rutas para Finanzas - Tarifas
    Route::post('/finanzas/tarifas', [FinanzasController::class, 'storeTarifa'])->name('finanzas.tarifas.store');
    Route::put('/finanzas/tarifas/{id}', [FinanzasController::class, 'updateTarifa'])->name('finanzas.tarifas.update');
    Route::delete('/finanzas/tarifas/{id}', [FinanzasController::class, 'destroyTarifa'])->name('finanzas.tarifas.destroy');

    // Rutas para Finanzas - Conciliaciones
    Route::post('/finanzas/conciliaciones', [FinanzasController::class, 'storeConciliacion'])->name('finanzas.conciliaciones.store');


    // ------- RUTAS PARA  MANTENIMIENTO -------
    //Página de mantenimiento :)
    Route::get('/mantenimiento', function (){
        return view('auth.mantenimiento');
    })->name('mantenimiento');

    Route::get('/mantenimiento', [MantenimientoController::class, 'index'])->name('mantenimiento');

    // Rutas de Mantenimiento
    Route::prefix('mantenimiento')->group(function () {
        // Programado
        Route::get('/m-programado', [MprogramadoController::class, 'index'])->name('mantenimiento.m-programado');
        Route::post('/programado', [MprogramadoController::class, 'store']);
        Route::get('/programado/{id}', [MprogramadoController::class, 'show']);
        Route::put('/programado/{id}', [MprogramadoController::class, 'update']);
        Route::delete('/programado/{id}', [MprogramadoController::class, 'destroy']);

        // Realizado
        Route::get('/m-realizado', [MrealizadoController::class, 'index'])->name('mantenimiento.m-realizado');
        Route::post('/realizado', [MrealizadoController::class, 'store']);
        Route::get('/realizado/{id}', [MrealizadoController::class, 'show']);
        Route::put('/realizado/{id}', [MrealizadoController::class, 'update']);
        Route::delete('/realizado/{id}', [MrealizadoController::class, 'destroy']);

        // Alertas
        Route::get('/m-alertas', [MalertasController::class, 'index'])->name('mantenimiento.m-alertas');
        Route::post('/alerta', [MalertasController::class, 'store']);
        Route::get('/alerta/{id}', [MalertasController::class, 'show']);
        Route::put('/alerta/{id}', [MalertasController::class, 'update']);
        Route::delete('/alerta/{id}', [MalertasController::class, 'destroy']);

        // Historial
        Route::get('/m-historial', [MhistorialController::class, 'index'])->name('mantenimiento.m-historial');
    });

    //Página de pasajero :)
    Route::get('/pasajerof', function (){
        return view('auth.pasajerof');
    })->name('pasajerof');

    // ------- RUTAS PARA PASAJERO -------
    Route::prefix('pasajero')->group(function () {
        // Registro de Pasajeros
        Route::get('/p-registro', [PregistroController::class, 'index'])->name('pasajero.p-registro');
        Route::post('/registro', [PregistroController::class, 'store'])->name('pasajero.registro.store');

        // Historial de Viajes
        Route::get('/p-historial', [PhistorialController::class, 'index'])->name('pasajero.p-historial');
        Route::get('/historial/data', [PhistorialController::class, 'getHistorial'])->name('pasajero.historial.data');

        // Quejas y Sugerencias
        Route::get('/p-quejas', [PquejasugerenciaController::class, 'index'])->name('pasajero.p-queja-sugerencia');
        Route::post('/quejas', [PquejasugerenciaController::class, 'store'])->name('pasajero.quejas.store');
    });

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

    // Rutas para TABLA Unidades
    Route::get('/unidades', [UnidadController::class, 'index'])->name('unidades.index');
    Route::post('/unidades', [UnidadController::class, 'store'])->name('unidades.store');
    Route::get('/unidades/{id}', [UnidadController::class, 'show'])->name('unidades.show');
    Route::put('/unidades/{id}', [UnidadController::class, 'update'])->name('unidades.update');
    Route::delete('/unidades/{id}', [UnidadController::class, 'destroy'])->name('unidades.destroy');

    // Rutas para TABLA Horarios
    Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios.index');
    Route::post('/horarios', [HorarioController::class, 'store'])->name('horarios.store');
    Route::get('/horarios/{id}', [HorarioController::class, 'show'])->name('horarios.show');
    Route::put('/horarios/{id}', [HorarioController::class, 'update'])->name('horarios.update');
    Route::delete('/horarios/{id}', [HorarioController::class, 'destroy'])->name('horarios.destroy');


    // Rutas para TABLA Rutas
    Route::get('/rutas', [RutaController::class, 'index'])->name('rutas.index');
    Route::post('/rutas', [RutaController::class, 'store'])->name('rutas.store');
    Route::get('/rutas/{id}', [RutaController::class, 'show'])->name('rutas.show');
    Route::put('/rutas/{id}', [RutaController::class, 'update'])->name('rutas.update');
    Route::delete('/rutas/{id}', [RutaController::class, 'destroy'])->name('rutas.destroy');

    // Rutas para TABLA Operadores
    Route::get('/operadores', [OperadorController::class, 'index'])->name('operadores.index');
    Route::post('/operadores', [OperadorController::class, 'store'])->name('operadores.store');
    Route::get('/operadores/{id}', [OperadorController::class, 'show'])->name('operadores.show');
    Route::put('/operadores/{id}', [OperadorController::class, 'update'])->name('operadores.update');
    Route::delete('/operadores/{id}', [OperadorController::class, 'destroy'])->name('operadores.destroy');

    // Rutas para TABLA incidentes (TincidenteController)
    Route::get('/tincidente', [TincidenteController::class, 'index'])->name('tincidente.index');
    Route::post('/tincidente', [TincidenteController::class, 'store'])->name('tincidente.store');
    Route::get('/tincidente/{id}', [TincidenteController::class, 'show'])->name('tincidente.show');
    Route::put('/tincidente/{id}', [TincidenteController::class, 'update'])->name('tincidente.update');
    Route::delete('/tincidente/{id}', [TincidenteController::class, 'destroy'])->name('tincidente.destroy');


