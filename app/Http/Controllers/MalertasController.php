<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MalertasModel;
use Illuminate\Http\Request;

class MalertasController extends Controller
{
    public function index()
    {
        $malertasModel = new MalertasModel();

        $alertasMantenimiento = $malertasModel->obtenerAlertasMantenimiento();
        $unidades = $malertasModel->obtenerUnidades();

        // Estadísticas para las tarjetas
        $alertasUrgentes = $malertasModel->contarAlertasPorEstado('urgente');
        $alertasProximas = $malertasModel->contarAlertasPorEstado('proximo');
        $alertasPendientes = $malertasModel->contarAlertasPorEstado('pendiente');
        $totalAlertas = $alertasUrgentes + $alertasProximas + $alertasPendientes;

        return view('mantenimiento.m-alertas', compact(
            'alertasMantenimiento',
            'unidades',
            'alertasUrgentes',
            'alertasProximas',
            'alertasPendientes',
            'totalAlertas'
        ));
    }

    public function store(Request $request)
    {
        $malertasModel = new MalertasModel();
        $resultado = $malertasModel->crearAlerta($request->all());

        return response()->json([
            'success' => $resultado,
            'message' => $resultado ? 'Alerta creada correctamente' : 'Error al crear alerta'
        ]);
    }

    public function show($id)
    {
        $malertasModel = new MalertasModel();
        $alerta = $malertasModel->obtenerAlertaPorId($id);

        return response()->json($alerta);
    }

    public function update(Request $request, $id)
    {
        $malertasModel = new MalertasModel();
        $resultado = $malertasModel->actualizarAlerta($id, $request->all());

        return response()->json([
            'success' => $resultado,
            'message' => $resultado ? 'Alerta actualizada correctamente' : 'Error al actualizar'
        ]);
    }

    public function destroy($id)
    {
        $malertasModel = new MalertasModel();
        $resultado = $malertasModel->eliminarAlerta($id);

        return response()->json([
            'success' => $resultado,
            'message' => $resultado ? 'Alerta eliminada correctamente' : 'Error al eliminar'
        ]);
    }
}
