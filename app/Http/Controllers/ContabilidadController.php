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
        dd($ingresos); // <-  para depurar


        $unidades  = $contabilidadModel->obtenerUnidades();
        $operadores = $contabilidadModel->obtenerOperadores();

        return view('auth.contabilidad', compact('ingresos', 'unidades', 'operadores' ));
    }
}


