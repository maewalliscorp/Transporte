<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MantenimientoModel;
use Illuminate\Http\Request;

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

    // Método para obtener datos de un mantenimiento específico
    public function obtenerMantenimiento($id)
    {
        $mantenimientoModel = new MantenimientoModel();
        $mantenimiento = $mantenimientoModel->obtenerMantenimientoPorId($id);

        return response()->json($mantenimiento);
    }

    // Método para actualizar mantenimiento
    public function actualizarMantenimiento(Request $request, $id)
    {
        $mantenimientoModel = new MantenimientoModel();
        $resultado = $mantenimientoModel->actualizarMantenimiento($id, $request->all());

        return response()->json([
            'success' => $resultado,
            'message' => $resultado ? 'Mantenimiento actualizado correctamente' : 'Error al actualizar'
        ]);
    }

    // Método para eliminar mantenimiento
    public function eliminarMantenimiento($id)
    {
        $mantenimientoModel = new MantenimientoModel();
        $resultado = $mantenimientoModel->eliminarMantenimiento($id);

        return response()->json([
            'success' => $resultado,
            'message' => $resultado ? 'Mantenimiento eliminado correctamente' : 'Error al eliminar'
        ]);
    }

    // Métodos para alertas
    public function obtenerAlerta($id)
    {
        $mantenimientoModel = new MantenimientoModel();
        $alerta = $mantenimientoModel->obtenerAlertaPorId($id);

        return response()->json($alerta);
    }

    public function actualizarAlerta(Request $request, $id)
    {
        $mantenimientoModel = new MantenimientoModel();
        $resultado = $mantenimientoModel->actualizarAlerta($id, $request->all());

        return response()->json([
            'success' => $resultado,
            'message' => $resultado ? 'Alerta actualizada correctamente' : 'Error al actualizar'
        ]);
    }

    public function eliminarAlerta($id)
    {
        $mantenimientoModel = new MantenimientoModel();
        $resultado = $mantenimientoModel->eliminarAlerta($id);

        return response()->json([
            'success' => $resultado,
            'message' => $resultado ? 'Alerta eliminada correctamente' : 'Error al eliminar'
        ]);
    }
}
