<?php

namespace App\Http\Controllers;

use App\Models\FinanzasModel;

class FinanzasController extends Controller
{
    public function index()
    {
        $finanzas = new FinanzasModel();

        $ingresos = $finanzas->obtenerIngresos();
        $egresos = $finanzas->obtenerEgresos();
        $tarifas = $finanzas->obtenerTarifas();
        $conciliaciones = $finanzas->obtenerConciliaciones();

        return view('auth.finanzas', compact(
            'ingresos',
            'egresos',
            'tarifas',
            'conciliaciones'
        ));
    }
}
