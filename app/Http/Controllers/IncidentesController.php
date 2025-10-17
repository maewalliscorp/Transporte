<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\IncidentesModel;

class IncidentesController extends Controller
{
    public function index()
    {
        $incidentesModel = new IncidentesModel();

        // ✅ Llamadas corregidas correctamente al modelo
        $incidentes = $incidentesModel->obtenerIncidentes();
        $incidentesPendientes = $incidentesModel->obtenerIncidentesPendientes();
        $historialIncidentes = $incidentesModel->obtenerIncidentes();
        $asignaciones = $incidentesModel->obtenerAsignacionesActivas();
        $unidades = $incidentesModel->obtenerUnidades();

        return view('auth.incidentes', compact(
            'incidentes',
            'incidentesPendientes',
            'historialIncidentes',
            'asignaciones',
            'unidades'
        ));
    }
}
