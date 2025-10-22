<?php

namespace App\Http\Controllers;

use App\Models\HorarioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorarioController extends Controller
{
    protected $horarioModel;

    public function __construct()
    {
        $this->horarioModel = new HorarioModel();
    }

    public function index()
    {
        $horarios = $this->horarioModel->obtenerTodos();
        return view('agregar.horarios', compact('horarios'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'horaSalida' => 'required|date_format:H:i',
                'horaLlegada' => 'required|date_format:H:i|after:horaSalida',
                'fecha' => 'required|date|after_or_equal:today'
            ], [
                'horaLlegada.after' => 'La hora de llegada debe ser posterior a la hora de salida.',
                'fecha.after_or_equal' => 'La fecha no puede ser anterior a hoy.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            // Verificar si ya existe un horario con los mismos datos
            if ($this->horarioModel->existeHorario(
                $request->horaSalida,
                $request->horaLlegada,
                $request->fecha
            )) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe un horario con la misma hora de salida, llegada y fecha.'
                ], 422);
            }

            $datos = [
                'horaSalida' => $request->horaSalida,
                'horaLlegada' => $request->horaLlegada,
                'fecha' => $request->fecha
            ];

            $resultado = $this->horarioModel->crear($datos);

            if ($resultado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Horario agregado correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al insertar el horario en la base de datos'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar horario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $horario = $this->horarioModel->obtenerPorId($id);

            if ($horario) {
                return response()->json([
                    'success' => true,
                    'data' => $horario
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

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'horaSalida' => 'required|date_format:H:i',
                'horaLlegada' => 'required|date_format:H:i|after:horaSalida',
                'fecha' => 'required|date'
            ], [
                'horaLlegada.after' => 'La hora de llegada debe ser posterior a la hora de salida.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            // Verificar que el horario existe
            $horario = $this->horarioModel->obtenerPorId($id);
            if (!$horario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Horario no encontrado'
                ], 404);
            }

            // Verificar si ya existe otro horario con los mismos datos
            if ($this->horarioModel->existeHorario(
                $request->horaSalida,
                $request->horaLlegada,
                $request->fecha,
                $id
            )) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe otro horario con la misma hora de salida, llegada y fecha.'
                ], 422);
            }

            $datos = [
                'horaSalida' => $request->horaSalida,
                'horaLlegada' => $request->horaLlegada,
                'fecha' => $request->fecha
            ];

            $resultado = $this->horarioModel->actualizar($id, $datos);

            if ($resultado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Horario actualizado correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el horario en la base de datos'
                ], 500);
            }

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
            // Verificar que el horario existe
            $horario = $this->horarioModel->obtenerPorId($id);
            if (!$horario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Horario no encontrado'
                ], 404);
            }

            $resultado = $this->horarioModel->eliminar($id);

            if ($resultado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Horario eliminado correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el horario de la base de datos'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar horario: ' . $e->getMessage()
            ], 500);
        }
    }
}
