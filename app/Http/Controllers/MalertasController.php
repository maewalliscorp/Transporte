<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MalertasModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MalertasController extends Controller
{
    public function index()
    {
        $malertasModel = new MalertasModel();

        $alertasMantenimiento = $malertasModel->obtenerAlertasMantenimiento();
        $unidades = $malertasModel->obtenerUnidades();

        // Estadísticas para las tarjetas
        $alertasUrgentes = $malertasModel->contarAlertasPorEstado('urgente');
        $alertasActivas = $malertasModel->contarAlertasPorEstado('activa');
        $alertasPendientes = $malertasModel->contarAlertasPorEstado('pendiente');
        $totalAlertas = $alertasUrgentes + $alertasActivas + $alertasPendientes;

        return view('mantenimiento.m-alertas', compact(
            'alertasMantenimiento',
            'unidades',
            'alertasUrgentes',
            'alertasActivas',
            'alertasPendientes',
            'totalAlertas'
        ));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Datos recibidos en store:', $request->all());

            $validated = $request->validate([
                'unidad' => 'required|integer|exists:unidad,id_unidad',
                'fechaUltimoMantenimiento' => 'required|date',
                'kmProxMantenimiento' => 'required|integer|min:0',
                'fechaProxMantenimiento' => 'required|date',
                'estadoAlerta' => 'required|in:urgente,activa,pendiente'
            ]);

            $malertasModel = new MalertasModel();
            $resultado = $malertasModel->crearAlerta($request->all());

            return response()->json([
                'success' => $resultado,
                'message' => $resultado ? 'Alerta creada correctamente' : 'Error al crear alerta'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en store: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $malertasModel = new MalertasModel();
            $alerta = $malertasModel->obtenerAlertaPorId($id);

            if (!$alerta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alerta no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $alerta
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            \Log::info('Datos recibidos en update:', $request->all());

            $validated = $request->validate([
                'unidad' => 'required|integer|exists:unidad,id_unidad',
                'fechaUltimoMantenimiento' => 'required|date',
                'kmProxMantenimiento' => 'required|integer|min:0',
                'fechaProxMantenimiento' => 'required|date',
                'estadoAlerta' => 'required|in:urgente,activa,pendiente'
            ]);

            $malertasModel = new MalertasModel();
            $resultado = $malertasModel->actualizarAlerta($id, $request->all());

            return response()->json([
                'success' => $resultado,
                'message' => $resultado ? 'Alerta actualizada correctamente' : 'Error al actualizar'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en update: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $malertasModel = new MalertasModel();
            $resultado = $malertasModel->eliminarAlerta($id);

            return response()->json([
                'success' => $resultado,
                'message' => $resultado ? 'Alerta eliminada correctamente' : 'Error al eliminar'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en destroy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud'
            ], 500);
        }
    }
}
