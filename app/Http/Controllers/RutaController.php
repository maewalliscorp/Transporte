<?php

namespace App\Http\Controllers;

use App\Models\RutaModel;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function index()
    {
        $rutaModel = new RutaModel();
        $rutas = $rutaModel->obtenerTodosConHorario();
        $horarios = $rutaModel->obtenerHorariosDisponibles();

        return view('agregar.rutas', compact('rutas', 'horarios'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:100',
                'origen' => 'required|string|max:100',
                'destino' => 'required|string|max:100',
                'duracion_estimada' => 'required|string|max:50',
                'id_horario' => 'required|integer|exists:horario,id_horario'
            ]);

            $rutaModel = new RutaModel();

            $datos = [
                'nombre' => $request->nombre,
                'origen' => $request->origen,
                'destino' => $request->destino,
                'duracion_estimada' => $request->duracion_estimada,
                'id_horario' => $request->id_horario
            ];

            $rutaModel->crear($datos);

            return response()->json([
                'success' => true,
                'message' => 'Ruta agregada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar ruta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:100',
                'origen' => 'required|string|max:100',
                'destino' => 'required|string|max:100',
                'duracion_estimada' => 'required|string|max:50',
                'id_horario' => 'required|integer|exists:horario,id_horario'
            ]);

            $rutaModel = new RutaModel();

            $datos = [
                'nombre' => $request->nombre,
                'origen' => $request->origen,
                'destino' => $request->destino,
                'duracion_estimada' => $request->duracion_estimada,
                'id_horario' => $request->id_horario
            ];

            $rutaModel->actualizar($id, $datos);

            return response()->json([
                'success' => true,
                'message' => 'Ruta actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar ruta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $rutaModel = new RutaModel();
            $rutaModel->eliminar($id);

            return response()->json([
                'success' => true,
                'message' => 'Ruta eliminada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar ruta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $rutaModel = new RutaModel();
            $ruta = $rutaModel->obtenerPorId($id);

            if ($ruta) {
                return response()->json([
                    'success' => true,
                    'data' => $ruta
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener ruta: ' . $e->getMessage()
            ], 500);
        }
    }
}
