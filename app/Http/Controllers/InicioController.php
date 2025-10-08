<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InicioModel;

class InicioController extends Controller
{
    /**
     * Muestra la vista de inicio.
     */
    public function index()
    {
        $inicioModel = new InicioModel();
        $disponibles = $inicioModel->obtenerDisponibles();
        $asignados   = $inicioModel->obtenerAsignados();

        $unidades  = $inicioModel->obtenerUnidades();
        $operadores = $inicioModel->obtenerOperadores();
        $rutas     = $inicioModel->obtenerRutas();
        $horarios  = $inicioModel->obtenerHorarios();


        return view('auth.inicio', compact('disponibles', 'asignados', 'unidades', 'operadores', 'rutas', 'horarios' ));


    }
}


