<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MhistorialModel;
use Illuminate\Http\Request;

class MhistorialController extends Controller
{
    public function index()
    {
        $mhistorialModel = new MhistorialModel();

        $historialMantenimiento = $mhistorialModel->obtenerHistorialMantenimiento();
        $unidades = $mhistorialModel->obtenerUnidades();

        // Estadísticas para las tarjetas
        $totalMantenimientos = $mhistorialModel->contarTotalMantenimientos();
        $mantenimientosPreventivos = $mhistorialModel->contarMantenimientosPorTipo('preventivo');
        $mantenimientosCorrectivos = $mhistorialModel->contarMantenimientosPorTipo('correctivo');
        $costoTotal = $mhistorialModel->obtenerCostoTotal();

        return view('mantenimiento.m-historial', compact(
            'historialMantenimiento',
            'unidades',
            'totalMantenimientos',
            'mantenimientosPreventivos',
            'mantenimientosCorrectivos',
            'costoTotal'
        ));
    }
}
