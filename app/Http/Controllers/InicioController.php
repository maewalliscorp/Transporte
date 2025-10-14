<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InicioModel;

class InicioController extends Controller
{
    /**
     * Muestra la vista de inicio.
     */
    public function index()
    {
        $inicioModel = new InicioModel();
        $disponibles = $inicioModel->obtenerDisponibles();
        $asignados   = $inicioModel->obtenerAsignados();

        $unidades  = $inicioModel->obtenerUnidades();
        $operadores = $inicioModel->obtenerOperadores();
        $rutas     = $inicioModel->obtenerRutas();
        $horarios  = $inicioModel->obtenerHorarios();


        return view('auth.inicio', compact('disponibles', 'asignados', 'unidades', 'operadores', 'rutas', 'horarios' ));


    }

    public function asignar(Request $request)
    {
        try {
            $inicioModel = new InicioModel();
            $db = $inicioModel->getConnection();

            $datos = $request->validate([
                'id_unidad' => 'required|integer',
                'id_operador' => 'required|integer',
                'id_ruta' => 'required|integer',
                'id_horario' => 'required|integer',
                'fecha' => 'required|date',
                'hora' => 'required'
            ]);

            // Insertar en la tabla asignacion
            $db->table('asignacion')->insert([
                'id_unidad' => $datos['id_unidad'],
                'id_operador' => $datos['id_operador'],
                'id_ruta' => $datos['id_ruta'],
                'fecha' => $datos['fecha'],
                'hora' => $datos['hora']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asignación realizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la asignación: ' . $e->getMessage()
            ], 500);
        }
    }
}
