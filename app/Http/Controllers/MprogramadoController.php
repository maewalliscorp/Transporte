<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MprogramadoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MprogramadoController extends Controller
{
    public function index()
    {
        $mprogramadoModel = new MprogramadoModel();

        $mantenimientosProgramados = $mprogramadoModel->obtenerMantenimientosProgramados();
        $unidades = $mprogramadoModel->obtenerUnidades();

        return view('mantenimiento.m-programado', compact(
            'mantenimientosProgramados',
            'unidades'
        ));
    }

    public function store(Request $request)
    {
        $mprogramadoModel = new MprogramadoModel();
        $resultado = $mprogramadoModel->crearMantenimientoProgramado($request->all());

        return response()->json([
            'success' => $resultado,
            'message' => $resultado ? 'Mantenimiento programado correctamente' : 'Error al programar mantenimiento'
        ]);
    }

    public function show($id)
    {
        $mprogramadoModel = new MprogramadoModel();
        $mantenimiento = $mprogramadoModel->obtenerMantenimientoPorId($id);

        return response()->json($mantenimiento);
    }

    public function update(Request $request, $id)
    {
        $mprogramadoModel = new MprogramadoModel();
        $resultado = $mprogramadoModel->actualizarMantenimiento($id, $request->all());

        return response()->json([
            'success' => $resultado,
            'message' => $resultado ? 'Mantenimiento actualizado correctamente' : 'Error al actualizar'
        ]);
    }

    public function destroy($id)
    {
        $mprogramadoModel = new MprogramadoModel();
        $resultado = $mprogramadoModel->eliminarMantenimiento($id);

        return response()->json([
            'success' => $resultado,
            'message' => $resultado ? 'Mantenimiento eliminado correctamente' : 'Error al eliminar'
        ]);
    }
}
