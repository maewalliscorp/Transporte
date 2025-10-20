<?php

namespace App\Http\Controllers;

use App\Models\TincidenteModel;
use Illuminate\Http\Request;

class TincidenteController extends Controller
{
    public function index()
    {
        $incidenteModel = new TincidenteModel();
        $incidentes = $incidenteModel->obtenerTodosConAsignacion();
        $asignaciones = $incidenteModel->obtenerAsignacionesDisponibles();

        return view('agregar.Tincidente', compact('incidentes', 'asignaciones'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_asignacion' => 'required|integer|exists:asignacion,id_asignacion',
                'descripcion' => 'required|string|max:500',
                'fecha' => 'required|date',
                'hora' => 'required',
                'estado' => 'required|string|in:pendiente,resuelto'
            ]);

            $incidenteModel = new TincidenteModel();

            $datos = [
                'id_asignacion' => $request->id_asignacion,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
                'estado' => $request->estado
            ];

            $incidenteModel->crear($datos);

            return response()->json([
                'success' => true,
                'message' => 'Incidente registrado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar incidente: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'id_asignacion' => 'required|integer|exists:asignacion,id_asignacion',
                'descripcion' => 'required|string|max:500',
                'fecha' => 'required|date',
                'hora' => 'required',
                'estado' => 'required|string|in:pendiente,resuelto'
            ]);

            $incidenteModel = new TincidenteModel();

            $datos = [
                'id_asignacion' => $request->id_asignacion,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
                'estado' => $request->estado
            ];

            $incidenteModel->actualizar($id, $datos);

            return response()->json([
                'success' => true,
                'message' => 'Incidente actualizado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar incidente: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $incidenteModel = new TincidenteModel();
            $incidenteModel->eliminar($id);

            return response()->json([
                'success' => true,
                'message' => 'Incidente eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar incidente: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $incidenteModel = new TincidenteModel();
            $incidente = $incidenteModel->obtenerPorId($id);

            if ($incidente) {
                return response()->json([
                    'success' => true,
                    'data' => $incidente
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Incidente no encontrado'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener incidente: ' . $e->getMessage()
            ], 500);
        }
    }
}
