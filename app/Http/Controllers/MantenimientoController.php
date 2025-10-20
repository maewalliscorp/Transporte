<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MantenimientoModel;

class MantenimientoController extends Controller
{
    public function index()
    {
        $mantenimientoModel = new MantenimientoModel();

        $mantenimientosProgramados = $mantenimientoModel->obtenerMantenimientosProgramados();
        $mantenimientosRealizados = $mantenimientoModel->obtenerMantenimientosRealizados();
        $alertasMantenimiento = $mantenimientoModel->obtenerAlertasMantenimiento();
        $historialMantenimiento = $mantenimientoModel->obtenerHistorialMantenimiento();
        $unidades = $mantenimientoModel->obtenerUnidades();
        $operadores = $mantenimientoModel->obtenerOperadores();

        return view('auth.mantenimiento', compact(
            'mantenimientosProgramados',
            'mantenimientosRealizados',
            'alertasMantenimiento',
            'historialMantenimiento',
            'unidades',
            'operadores'
        ));
    }
}
