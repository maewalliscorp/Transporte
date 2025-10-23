<?php

namespace App\Http\Controllers;

use App\Models\RutaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RutaController extends Controller
{
    protected $rutaModel;

    public function __construct()
    {
        $this->rutaModel = new RutaModel();
    }

    public function index()
    {
        $rutas = $this->rutaModel->obtenerTodosConHorario();
        $horarios = $this->rutaModel->obtenerHorariosDisponibles();

        return view('agregar.rutas', compact('rutas', 'horarios'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:100',
                'origen' => 'required|string|max:100',
                'destino' => 'required|string|max:100',
                'duracion_estimada' => 'required|string|max:50',
                'id_horario' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            // Verificar si ya existe una ruta con el mismo nombre
            if ($this->rutaModel->existeRuta($request->nombre)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: Ya existe una ruta con el nombre "' . $request->nombre . '"'
                ], 422);
            }

            // Verificar si el horario ya está ocupado
            if ($this->rutaModel->horarioOcupado($request->id_horario)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: El horario seleccionado ya está asignado a otra ruta'
                ], 422);
            }

            $datos = [
                'nombre' => $request->nombre,
                'origen' => $request->origen,
                'destino' => $request->destino,
                'duracion_estimada' => $request->duracion_estimada,
                'id_horario' => $request->id_horario
            ];

            $resultado = $this->rutaModel->crear($datos);

            if ($resultado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ruta agregada correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al insertar la ruta en la base de datos'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar ruta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $ruta = $this->rutaModel->obtenerPorId($id);

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

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:100',
                'origen' => 'required|string|max:100',
                'destino' => 'required|string|max:100',
                'duracion_estimada' => 'required|string|max:50',
                'id_horario' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            // Verificar que la ruta existe
            $ruta = $this->rutaModel->obtenerPorId($id);
            if (!$ruta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruta no encontrada'
                ], 404);
            }

            // Verificar si ya existe otra ruta con el mismo nombre
            if ($this->rutaModel->existeRuta($request->nombre, $id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: Ya existe otra ruta con el nombre "' . $request->nombre . '"'
                ], 422);
            }

            // Verificar si el horario ya está ocupado por otra ruta
            if ($this->rutaModel->horarioOcupado($request->id_horario, $id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: El horario seleccionado ya está asignado a otra ruta'
                ], 422);
            }

            $datos = [
                'nombre' => $request->nombre,
                'origen' => $request->origen,
                'destino' => $request->destino,
                'duracion_estimada' => $request->duracion_estimada,
                'id_horario' => $request->id_horario
            ];

            $resultado = $this->rutaModel->actualizar($id, $datos);

            if ($resultado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ruta actualizada correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la ruta en la base de datos'
                ], 500);
            }

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
            // Verificar que la ruta existe
            $ruta = $this->rutaModel->obtenerPorId($id);
            if (!$ruta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruta no encontrada'
                ], 404);
            }

            $resultado = $this->rutaModel->eliminar($id);

            if ($resultado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ruta eliminada correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la ruta de la base de datos'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar ruta: ' . $e->getMessage()
            ], 500);
        }
    }
}
