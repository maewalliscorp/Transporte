<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContabilidadModel;

class ContabilidadController extends Controller
{
    /**
     * Muestra la vista de contabilidad.
     */
    public function index()
    {
        $contabilidadModel = new ContabilidadModel();

        $ingresos = $contabilidadModel->obtenerIngresos();
        $egresos = $contabilidadModel->obtenerEgresos();
        $totalEgresos = $contabilidadModel->obtenerTotalEgresos();

        $tarifas = $contabilidadModel->obtenerTarifas();
        $bancarios = $contabilidadModel->obtenerBancarios();
        $totalBancarios = $contabilidadModel->obtenerTotalBancarios();


        $rutas = $contabilidadModel->obtenerRutas();
        $unidades  = $contabilidadModel->obtenerUnidades();
        $operadores = $contabilidadModel->obtenerOperadores();

        return view('auth.contabilidad', compact(
            'ingresos',
            'egresos',
            'totalEgresos',
            'unidades',
            'operadores',
            'tarifas',
            'bancarios',
            'rutas',
            'totalBancarios'
        ));

    }
}
