<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MonitoreoModel;
use Illuminate\Http\Request;

class MonitoreoController extends Controller
{
    /**
     * Muestra la vista de monitoreo.
     */
    public function index()
    {
        $monitoreoModel = new MonitoreoModel();
        $unidades = $monitoreoModel->obtenerUnidadesConGPS();
        $unidadesPuntualidad = $monitoreoModel->obtenerUnidadesParaPuntualidad();

        return view('auth.monitoreo', compact('unidades', 'unidadesPuntualidad'));
    }

    /**
     * Obtener ubicación en tiempo real de una unidad
     */
    public function getUbicacionUnidad($id_unidad)
    {
        try {
            $monitoreoModel = new MonitoreoModel();
            $db = $monitoreoModel->getConnection();

            $sql = "SELECT latitud, longitud, velocidad, timestamp FROM ubicaciones_gps WHERE id_unidad = ? ORDER BY timestamp DESC LIMIT 1";
            $result = $db->select($sql, [$id_unidad]);

            if (count($result) > 0) {
                return response()->json([
                    'success' => true,
                    'data' => (array)$result[0]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se encontró ubicación para esta unidad'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener historial de ruta de una unidad
     */
    public function getHistorialRuta($id_unidad, Request $request)
    {
        try {
            $fecha = $request->get('fecha', date('Y-m-d'));

            $monitoreoModel = new MonitoreoModel();
            $historial = $monitoreoModel->obtenerHistorialRuta($id_unidad, $fecha);

            return response()->json([
                'success' => true,
                'data' => $historial
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar ubicación simulada (para pruebas)
     */
    public function simularUbicacion(Request $request)
    {
        try {
            $monitoreoModel = new MonitoreoModel();
            $db = $monitoreoModel->getConnection();

            $datos = $request->validate([
                'id_unidad' => 'required|integer',
                'latitud' => 'required|numeric',
                'longitud' => 'required|numeric',
                'velocidad' => 'nullable|numeric'
            ]);

            // Insertar en la tabla de ubicaciones_gps
            $db->table('ubicaciones_gps')->insert([
                'id_unidad' => $datos['id_unidad'],
                'latitud' => $datos['latitud'],
                'longitud' => $datos['longitud'],
                'velocidad' => $datos['velocidad'] ?? 0,
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ubicación actualizada'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
