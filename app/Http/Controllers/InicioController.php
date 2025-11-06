<?php

namespace App\Http\Controllers;

use App\Models\InicioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InicioController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $inicioModel = new InicioModel();

        $disponibles = $inicioModel->obtenerDisponibles();
        $asignados   = $inicioModel->obtenerAsignados();

        $unidades   = $inicioModel->obtenerUnidades();
        $operadores = $inicioModel->obtenerOperadores();
        $rutas      = $inicioModel->obtenerRutas();
        $horarios   = $inicioModel->obtenerHorarios();

        return view('auth.inicio', compact(
            'disponibles', 'asignados',
            'unidades', 'operadores', 'rutas', 'horarios'
        ));
    }

    public function asignar(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_unidad' => 'required|integer',
                'id_operador' => 'required|integer',
                'id_ruta' => 'required|integer',
                'fecha' => 'required|date',
                'hora' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $inicioModel = new InicioModel();
            $db = DB::connection();

            // Verificar si ya está asignado (unidad u operador) en esa fecha
            $asignacionExistente = $db->table('asignacion')
                ->where(function ($query) use ($request) {
                    $query->where('id_unidad', $request->id_unidad)
                        ->orWhere('id_operador', $request->id_operador);
                })
                ->where('fecha', $request->fecha)
                ->first();

            if ($asignacionExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta unidad u operador ya tienen una asignación para la fecha seleccionada.'
                ], 422);
            }

            $resultado = $inicioModel->crearAsignacion([
                'id_unidad' => $request->id_unidad,
                'id_operador' => $request->id_operador,
                'id_ruta' => $request->id_ruta,
                'fecha' => $request->fecha,
                'hora' => $request->hora
            ]);

            return response()->json([
                'success' => (bool)$resultado,
                'message' => $resultado
                    ? 'Asignación realizada correctamente.'
                    : 'Error al insertar la asignación en la base de datos.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la asignación: ' . $e->getMessage()
            ], 500);
        }
    }
}
