<?php

namespace App\Http\Controllers;

use App\Models\OperadorModel;
use Illuminate\Http\Request;

class OperadorController extends Controller
{
    public function index()
    {
        $operadorModel = new OperadorModel();
        $operadores = $operadorModel->obtenerTodosConUsuario();
        $usuarios = $operadorModel->obtenerUsuariosDisponibles();

        return view('agregar.operadores', compact('operadores', 'usuarios'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:users,id|unique:operador,id',
                'codigo' => 'required|numeric|unique:operador,codigo',
                'licencia' => 'required|string|max:20|unique:operador,licencia',
                'telefono' => 'required|string|max:15',
                'estado' => 'required|in:activo,inactivo,suspendido'
            ]);

            $operadorModel = new OperadorModel();

            $datos = [
                'id' => $request->id,
                'codigo' => $request->codigo,
                'licencia' => $request->licencia,
                'telefono' => $request->telefono,
                'estado' => $request->estado
            ];

            $operadorModel->crear($datos);

            return response()->json([
                'success' => true,
                'message' => 'Operador agregado correctamente',
                'codigo' => $request->codigo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar operador: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'codigo' => 'required|numeric|unique:operador,codigo,' . $id . ',id_operator',
                'licencia' => 'required|string|max:20|unique:operador,licencia,' . $id . ',id_operator',
                'telefono' => 'required|string|max:15',
                'estado' => 'required|in:activo,inactivo,suspendido'
            ]);

            $operadorModel = new OperadorModel();

            $datos = [
                'codigo' => $request->codigo,
                'licencia' => $request->licencia,
                'telefono' => $request->telefono,
                'estado' => $request->estado
            ];

            $operadorModel->actualizar($id, $datos);

            return response()->json([
                'success' => true,
                'message' => 'Operador actualizado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar operador: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $operadorModel = new OperadorModel();
            $operadorModel->eliminar($id);

            return response()->json([
                'success' => true,
                'message' => 'Operador eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar operador: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $operadorModel = new OperadorModel();
            $operador = $operadorModel->obtenerPorId($id);

            if ($operador) {
                $usuarios = $operadorModel->obtenerUsuariosDisponibles($operador['user_id']);

                return response()->json([
                    'success' => true,
                    'data' => $operador,
                    'usuarios' => $usuarios
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Operador no encontrado'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener operador: ' . $e->getMessage()
            ], 500);
        }
    }
}
