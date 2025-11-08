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
        $validator = Validator::make($request->all(), [
            'unidad' => 'required|integer|exists:unidad,id_unidad',
            'tipo_mantenimiento' => 'required|in:Preventivo,Correctivo',
            'fecha_programada' => 'required|date',
            'motivo' => 'required|string|max:255',
            'kmActual' => 'required|integer|min:0',
            'estado' => 'required|in:pendiente,completado,urgente',
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

        $mprogramadoModel = new MprogramadoModel();
        $resultado = $mprogramadoModel->crearMantenimientoProgramado($request->all());

        return response()->json($resultado);
    }

    public function show($id)
    {
        $mprogramadoModel = new MprogramadoModel();
        $mantenimiento = $mprogramadoModel->obtenerMantenimientoPorId($id);

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
            'estado' => 'required|in:pendiente,completado,urgente',
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

        $mprogramadoModel = new MprogramadoModel();
        $resultado = $mprogramadoModel->actualizarMantenimiento($id, $request->all());

        return response()->json($resultado);
    }

    public function destroy($id)
    {
        $mprogramadoModel = new MprogramadoModel();
        $resultado = $mprogramadoModel->eliminarMantenimiento($id);

        return response()->json($resultado);
    }
}
