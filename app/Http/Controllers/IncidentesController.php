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
            DB::beginTransaction();

            $request->validate([
                'id_asignacion' => 'required|exists:asignacion,id_asignacion',
                'fecha' => 'required|date',
                'hora' => 'required',
                'descripcion' => 'required|string|max:1000'
            ]);

            $incidentesModel = new IncidentesModel();

            // Insertar el incidente
            DB::table('incidente')->insert([
                'id_asignacion' => $request->id_asignacion,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
                'descripcion' => $request->descripcion,
                'estado' => 'Pendiente'
               // 'solucion' => null
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Incidente registrado correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el incidente: ' . $e->getMessage()
            ], 500);
        }
    }
    /*
        public function solucionar(Request $request)
        {
            try {
                DB::beginTransaction();

                $request->validate([
                    'id_incidente' => 'required|exists:incidente,id_incidente',
                    'solucion' => 'required|string|max:1000'
                ]);

                // Actualizar el incidente con la solución
                DB::table('incidente')
                    ->where('id_incidente', $request->id_incidente)
                    ->update([
                        'solucion' => $request->solucion,
                        'estado' => 'Solucionado'
                    ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Solución asignada correctamente'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error al asignar la solución: ' . $e->getMessage()
                ], 500);
            }
        }
        */
}
