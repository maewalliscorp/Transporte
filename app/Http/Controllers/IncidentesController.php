<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\IncidentesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncidentesController extends Controller
{
    public function index()
    {
        $incidentesModel = new IncidentesModel();

        $incidentes = $incidentesModel->obtenerIncidentes();
        $incidentesPendientes = $incidentesModel->obtenerIncidentesPendientes();
        $asignaciones = $incidentesModel->obtenerAsignacionesActivas();
        $unidades = $incidentesModel->obtenerUnidades();

        return view('auth.incidentes', compact(
            'incidentes',
            'incidentesPendientes',
            'asignaciones',
            'unidades'
        ));
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

            $incidentesModel = new IncidentesModel();

            $datos = [
                'id_asignacion' => $request->id_asignacion,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
                'estado' => $request->estado
            ];

            $incidentesModel->crear($datos);

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

            $incidentesModel = new IncidentesModel();

            $datos = [
                'id_asignacion' => $request->id_asignacion,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
                'estado' => $request->estado
            ];

            $incidentesModel->actualizar($id, $datos);

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
            $incidentesModel = new IncidentesModel();
            $incidentesModel->eliminar($id);

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
            $incidentesModel = new IncidentesModel();
            $incidente = $incidentesModel->obtenerPorId($id);

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

    public function solucionar(Request $request, $id)
    {
        try {
            // Validar que la solicitud sea JSON
            if (!$request->expectsJson() && $request->isJson()) {
                $data = $request->json()->all();
                $solucion = $data['solucion'] ?? null;
            } else {
                $solucion = $request->solucion;
            }

            // Validar que la solución no esté vacía
            if (empty($solucion)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La solución es requerida'
                ], 422);
            }

            // Validar longitud
            if (strlen($solucion) > 500) {
                return response()->json([
                    'success' => false,
                    'message' => 'La solución no puede exceder los 500 caracteres'
                ], 422);
            }

            $incidentesModel = new IncidentesModel();

            // Verificar que el incidente existe
            $incidente = $incidentesModel->obtenerPorId($id);
            if (!$incidente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incidente no encontrado'
                ], 404);
            }

            $resultado = $incidentesModel->agregarSolucion($id, $solucion);

            if ($resultado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Solución asignada correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo asignar la solución'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Error en solucionar incidente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar la solución: ' . $e->getMessage()
            ], 500);
        }
    }
}
