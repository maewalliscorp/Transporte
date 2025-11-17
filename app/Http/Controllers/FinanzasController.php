<?php

namespace App\Http\Controllers;

use App\Models\FinanzasModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanzasController extends Controller
{
    public function index()
    {
        $finanzas = new FinanzasModel();

        $ingresos = $finanzas->obtenerIngresos();
        $egresos = $finanzas->obtenerEgresos();
        $tarifas = $finanzas->obtenerTarifas();
        $conciliaciones = $finanzas->obtenerConciliaciones();
        $rutas = $finanzas->obtenerRutas();

        return view('auth.finanzas', compact(
            'ingresos',
            'egresos',
            'tarifas',
            'conciliaciones',
            'rutas'
        ));
    }

    // Métodos para Ingresos
    public function storeIngreso(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'concepto' => 'required|string|max:255',
                'monto' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $finanzas = new FinanzasModel();

            // Obtener el ID del contador del usuario actual (si existe)
            $contadorId = $this->obtenerContadorId(Auth::id());

            $datos = [
                'tipoMovimiento' => 'ingreso',
                'concepto' => $request->concepto,
                'monto' => $request->monto,
                'fecha' => now()->format('Y-m-d'),
                'hora' => now()->format('H:i:s'),
                'contadorFK' => $contadorId
            ];

            $resultado = $finanzas->crearMovimiento($datos);

            return response()->json([
                'success' => (bool)$resultado,
                'message' => $resultado ? 'Ingreso registrado correctamente.' : 'Error al registrar el ingreso.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar ingreso: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateIngreso(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'concepto' => 'required|string|max:255',
                'monto' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $finanzas = new FinanzasModel();

            $datos = [
                'concepto' => $request->concepto,
                'monto' => $request->monto,
            ];

            $resultado = $finanzas->actualizarMovimiento($id, $datos);

            return response()->json([
                'success' => (bool)$resultado,
                'message' => $resultado ? 'Ingreso actualizado correctamente.' : 'Error al actualizar el ingreso.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar ingreso: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyIngreso($id)
    {
        try {
            $finanzas = new FinanzasModel();
            $resultado = $finanzas->eliminarMovimiento($id);

            return response()->json([
                'success' => (bool)$resultado,
                'message' => $resultado ? 'Ingreso eliminado correctamente.' : 'Error al eliminar el ingreso.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar ingreso: ' . $e->getMessage()
            ], 500);
        }
    }

    // Métodos para Egresos
    public function storeEgreso(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'concepto' => 'required|string|max:255',
                'monto' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $finanzas = new FinanzasModel();

            $contadorId = $this->obtenerContadorId(Auth::id());

            $datos = [
                'tipoMovimiento' => 'egreso',
                'concepto' => $request->concepto,
                'monto' => $request->monto,
                'fecha' => now()->format('Y-m-d'),
                'hora' => now()->format('H:i:s'),
                'contadorFK' => $contadorId
            ];

            $resultado = $finanzas->crearMovimiento($datos);

            return response()->json([
                'success' => (bool)$resultado,
                'message' => $resultado ? 'Egreso registrado correctamente.' : 'Error al registrar el egreso.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar egreso: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateEgreso(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'concepto' => 'required|string|max:255',
                'monto' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $finanzas = new FinanzasModel();

            $datos = [
                'concepto' => $request->concepto,
                'monto' => $request->monto,
            ];

            $resultado = $finanzas->actualizarMovimiento($id, $datos);

            return response()->json([
                'success' => (bool)$resultado,
                'message' => $resultado ? 'Egreso actualizado correctamente.' : 'Error al actualizar el egreso.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar egreso: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyEgreso($id)
    {
        try {
            $finanzas = new FinanzasModel();
            $resultado = $finanzas->eliminarMovimiento($id);

            return response()->json([
                'success' => (bool)$resultado,
                'message' => $resultado ? 'Egreso eliminado correctamente.' : 'Error al eliminar el egreso.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar egreso: ' . $e->getMessage()
            ], 500);
        }
    }

    // Métodos para Tarifas
    public function storeTarifa(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_ruta' => 'required|integer',
                'tarifaBaseRuta' => 'required|numeric|min:0',
                'tipoPasajero' => 'required|string|max:255',
                'descuentoPasajero' => 'nullable|numeric|min:0|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $finanzas = new FinanzasModel();

            $tarifaBase = $request->tarifaBaseRuta;
            $descuento = $request->descuentoPasajero ?? 0;
            $tarifaFinal = $tarifaBase * (1 - ($descuento / 100));

            $datos = [
                'id_ruta' => $request->id_ruta,
                'tarifaBaseRuta' => $tarifaBase,
                'tipoPasajero' => $request->tipoPasajero,
                'descuentoPasajero' => $descuento,
                'tarifaFinal' => $tarifaFinal
            ];

            $resultado = $finanzas->crearTarifa($datos);

            return response()->json([
                'success' => (bool)$resultado,
                'message' => $resultado ? 'Tarifa registrada correctamente.' : 'Error al registrar la tarifa.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar tarifa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateTarifa(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_ruta' => 'required|integer',
                'tarifaBaseRuta' => 'required|numeric|min:0',
                'tipoPasajero' => 'required|string|max:255',
                'descuentoPasajero' => 'nullable|numeric|min:0|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $finanzas = new FinanzasModel();

            $tarifaBase = $request->tarifaBaseRuta;
            $descuento = $request->descuentoPasajero ?? 0;
            $tarifaFinal = $tarifaBase * (1 - ($descuento / 100));

            $datos = [
                'id_ruta' => $request->id_ruta,
                'tarifaBaseRuta' => $tarifaBase,
                'tipoPasajero' => $request->tipoPasajero,
                'descuentoPasajero' => $descuento,
                'tarifaFinal' => $tarifaFinal
            ];

            $resultado = $finanzas->actualizarTarifa($id, $datos);

            return response()->json([
                'success' => (bool)$resultado,
                'message' => $resultado ? 'Tarifa actualizada correctamente.' : 'Error al actualizar la tarifa.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar tarifa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyTarifa($id)
    {
        try {
            $finanzas = new FinanzasModel();
            $resultado = $finanzas->eliminarTarifa($id);

            return response()->json([
                'success' => (bool)$resultado,
                'message' => $resultado ? 'Tarifa eliminada correctamente.' : 'Error al eliminar la tarifa.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar tarifa: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para Conciliaciones
    public function storeConciliacion(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'movimientoBancarioIdFK' => 'required|integer|exists:movimientobancario,id_movimiento',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $finanzas = new FinanzasModel();

            $datos = [
                'movimientoBancarioIdFK' => $request->movimientoBancarioIdFK,
                'fechaRegistro' => now()->format('Y-m-d')
            ];

            $resultado = $finanzas->crearConciliacion($datos);

            return response()->json([
                'success' => (bool)$resultado,
                'message' => $resultado ? 'Conciliación registrada correctamente.' : 'Error al registrar la conciliación.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar conciliación: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método auxiliar para obtener el ID del contador
    private function obtenerContadorId($userId)
    {
        $contador = DB::table('contador')
            ->where('id', $userId)
            ->first();

        return $contador ? $contador->id_contador : null;
    }
}
