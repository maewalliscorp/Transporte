<?php

namespace App\Http\Controllers;

use App\Models\HorarioModel;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index()
    {
        $horarioModel = new HorarioModel();
        $horarios = $horarioModel->obtenerTodos();

        return view('agregar.horarios', compact('horarios'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'horaSalida' => 'required|date_format:H:i',
                'horaLlegada' => 'required|date_format:H:i',
                'fecha' => 'required|date'
            ]);

            $horarioModel = new HorarioModel();

            $datos = [
                'horaSalida' => $request->horaSalida,
                'horaLlegada' => $request->horaLlegada,
                'fecha' => $request->fecha
            ];

            $horarioModel->crear($datos);

            return response()->json([
                'success' => true,
                'message' => 'Horario agregado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar horario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'horaSalida' => 'required|date_format:H:i',
                'horaLlegada' => 'required|date_format:H:i',
                'fecha' => 'required|date'
            ]);

            $horarioModel = new HorarioModel();

            $datos = [
                'horaSalida' => $request->horaSalida,
                'horaLlegada' => $request->horaLlegada,
                'fecha' => $request->fecha
            ];

            $horarioModel->actualizar($id, $datos);

            return response()->json([
                'success' => true,
                'message' => 'Horario actualizado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar horario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $horarioModel = new HorarioModel();
            $horarioModel->eliminar($id);

            return response()->json([
                'success' => true,
                'message' => 'Horario eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar horario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $horarioModel = new HorarioModel();
            $horario = $horarioModel->getConnection()
                ->table('horario')
                ->where('id_horario', $id)
                ->first();

            if ($horario) {
                return response()->json([
                    'success' => true,
                    'data' => (array)$horario
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Horario no encontrado'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener horario: ' . $e->getMessage()
            ], 500);
        }
    }
}
