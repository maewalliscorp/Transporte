<?php

namespace App\Http\Controllers;

use App\Models\UnidadModel;
use Illuminate\Http\Request;

class UnidadController extends Controller
{
    public function index()
    {
        $unidadModel = new UnidadModel();
        $unidades = $unidadModel->obtenerTodos();

        return view('agregar.unidades', compact('unidades'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'placa' => 'required|string|max:10|unique:unidad,placa',
                'modelo' => 'required|string|max:100',
                'capacidad' => 'required|integer|min:1'
            ]);

            $unidadModel = new UnidadModel();

            $datos = [
                'placa' => $request->placa,
                'modelo' => $request->modelo,
                'capacidad' => $request->capacidad
            ];

            $unidadModel->crear($datos);

            return response()->json([
                'success' => true,
                'message' => 'Unidad agregada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar unidad: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'placa' => 'required|string|max:10|unique:unidad,placa,' . $id . ',id_unidad',
                'modelo' => 'required|string|max:100',
                'capacidad' => 'required|integer|min:1'
            ]);

            $unidadModel = new UnidadModel();

            $datos = [
                'placa' => $request->placa,
                'modelo' => $request->modelo,
                'capacidad' => $request->capacidad
            ];

            $unidadModel->actualizar($id, $datos);

            return response()->json([
                'success' => true,
                'message' => 'Unidad actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar unidad: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $unidadModel = new UnidadModel();
            $unidadModel->eliminar($id);

            return response()->json([
                'success' => true,
                'message' => 'Unidad eliminada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar unidad: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $unidadModel = new UnidadModel();
            $unidad = $unidadModel->getConnection()
                ->table('unidad')
                ->where('id_unidad', $id)
                ->first();

            if ($unidad) {
                return response()->json([
                    'success' => true,
                    'data' => (array)$unidad
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Unidad no encontrada'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener unidad: ' . $e->getMessage()
            ], 500);
        }
    }
}
