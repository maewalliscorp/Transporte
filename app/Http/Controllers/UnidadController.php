<?php

namespace App\Http\Controllers;

use App\Models\UnidadModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnidadController extends Controller
{
    protected $unidadModel;

    public function __construct()
    {
        $this->unidadModel = new UnidadModel();
    }

    public function index()
    {
        $unidades = $this->unidadModel->obtenerTodos();
        return view('agregar.unidades', compact('unidades'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'placa' => 'required|string|max:10',
                'modelo' => 'required|string|max:100',
                'capacidad' => 'required|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            // Verificar si ya existe una unidad con la misma placa
            if ($this->unidadModel->existePlaca($request->placa)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una unidad con la misma placa.'
                ], 422);
            }

            $datos = [
                'placa' => strtoupper($request->placa),
                'modelo' => $request->modelo,
                'capacidad' => $request->capacidad
            ];

            $resultado = $this->unidadModel->crear($datos);

            if ($resultado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unidad agregada correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al insertar la unidad en la base de datos'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar unidad: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $unidad = $this->unidadModel->obtenerPorId($id);

            if ($unidad) {
                return response()->json([
                    'success' => true,
                    'data' => $unidad
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

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'placa' => 'required|string|max:10',
                'modelo' => 'required|string|max:100',
                'capacidad' => 'required|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            // Verificar que la unidad existe
            $unidad = $this->unidadModel->obtenerPorId($id);
            if (!$unidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unidad no encontrada'
                ], 404);
            }

            // Verificar si ya existe otra unidad con la misma placa
            if ($this->unidadModel->existePlaca($request->placa, $id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe otra unidad con la misma placa.'
                ], 422);
            }

            $datos = [
                'placa' => strtoupper($request->placa),
                'modelo' => $request->modelo,
                'capacidad' => $request->capacidad
            ];

            $resultado = $this->unidadModel->actualizar($id, $datos);

            if ($resultado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unidad actualizada correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la unidad en la base de datos'
                ], 500);
            }

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
            // Verificar que la unidad existe
            $unidad = $this->unidadModel->obtenerPorId($id);
            if (!$unidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unidad no encontrada'
                ], 404);
            }

            $resultado = $this->unidadModel->eliminar($id);

            if ($resultado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unidad eliminada correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la unidad de la base de datos'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar unidad: ' . $e->getMessage()
            ], 500);
        }
    }
}
