<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MrealizadoModel;
use Illuminate\Http\Request;

class MrealizadoController extends Controller
{
    public function index()
    {
        $mrealizadoModel = new MrealizadoModel();

        $mantenimientosRealizados = $mrealizadoModel->obtenerMantenimientosRealizados();
        $unidades = $mrealizadoModel->obtenerUnidades();
        $operadores = $mrealizadoModel->obtenerOperadores();

        return view('mantenimiento.m-realizado', compact(
            'mantenimientosRealizados',
            'unidades',
            'operadores'
        ));
    }

    public function store(Request $request)
    {
        $mrealizadoModel = new MrealizadoModel();
        $resultado = $mrealizadoModel->crearMantenimientoRealizado($request->all());

        return response()->json([
            'success' => $resultado,
            'message' => $resultado ? 'Mantenimiento registrado correctamente' : 'Error al registrar mantenimiento'
        ]);
    }

    public function show($id)
    {
        $mrealizadoModel = new MrealizadoModel();
        $mantenimiento = $mrealizadoModel->obtenerMantenimientoPorId($id);

        return response()->json($mantenimiento);
    }

    public function update(Request $request, $id)
    {
        $mrealizadoModel = new MrealizadoModel();
        $resultado = $mrealizadoModel->actualizarMantenimiento($id, $request->all());

        return response()->json([
            'success' => $resultado,
            'message' => $resultado ? 'Mantenimiento actualizado correctamente' : 'Error al actualizar'
        ]);
    }

    public function destroy($id)
    {
        $mrealizadoModel = new MrealizadoModel();
        $resultado = $mrealizadoModel->eliminarMantenimiento($id);

        return response()->json([
            'success' => $resultado,
            'message' => $resultado ? 'Mantenimiento eliminado correctamente' : 'Error al eliminar'
        ]);
    }
}
