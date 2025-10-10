<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContabilidadModel;
use App\Models\InicioModel;

class ContabilidadController extends Controller
{
    /**
     * Muestra la vista de contabilidad.
     */
    public function index()
    {
        $contabilidadModel = new ContabilidadModel();
        $ingresos = $contabilidadModel->obtenerIngresos();
        $egresos = $contabilidadModel->obtenerEgresos(); // NUEVO
        $totalEgresos = $contabilidadModel->obtenerTotalEgresos(); // NUEVO

        $unidades  = $contabilidadModel->obtenerUnidades();
        $operadores = $contabilidadModel->obtenerOperadores();

        return view('auth.contabilidad', compact(
            'ingresos',
            'egresos', // NUEVO
            'totalEgresos', // NUEVO
            'unidades',
            'operadores'
        ));
    }
}
