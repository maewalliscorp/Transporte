<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MrealizadoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MrealizadoController extends Controller
{
    public function index()
    {
        $mrealizadoModel = new MrealizadoModel();

        $mantenimientosRealizados = $mrealizadoModel->obtenerMantenimientosRealizados();
        $unidades = $mrealizadoModel->obtenerUnidades();

        return view('mantenimiento.m-realizado', compact(
            'mantenimientosRealizados',
            'unidades'
        ));
    }

    public function show($id)
    {
        $mrealizadoModel = new MrealizadoModel();
        $mantenimiento = $mrealizadoModel->obtenerMantenimientoPorId($id);

        return response()->json($mantenimiento);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'unidad' => 'required|integer|exists:unidad,id_unidad',
            'tipo_mantenimiento' => 'required|in:Preventivo,Correctivo',
            'fecha_programada' => 'required|date',
            'motivo' => 'required|string|max:255',
            'kmActual' => 'required|integer|min:0',
            'estado' => 'required|in:completado',
            'piezas' => 'nullable|string|max:500',
            'costo' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $mrealizadoModel = new MrealizadoModel();
        $resultado = $mrealizadoModel->actualizarMantenimiento($id, $request->all());

        return response()->json($resultado);
    }

    public function destroy($id)
    {
        $mrealizadoModel = new MrealizadoModel();
        $resultado = $mrealizadoModel->eliminarMantenimiento($id);

        return response()->json($resultado);
    }
}
