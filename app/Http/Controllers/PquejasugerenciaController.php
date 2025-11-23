<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PquejasugerenciaModel;

class PquejasugerenciaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder a esta página.');
        }

        return view('pasajero.p-queja-sugerencia', [
            'user' => $user
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos
            $validated = $request->validate([
                'quejaSugerencia' => 'required|string|max:500',
                'tipoComentario' => 'required|in:queja,sugerencia,felicitacion',
                'areaQS' => 'required|string'
            ]);

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado.'
                ], 401);
            }

            // Buscar el id_pasajero
            $pasajero = DB::table('pasajero')
                ->where('id', $user->id)
                ->first();

            if (!$pasajero) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró información de pasajero. Completa tu registro primero.'
                ], 404);
            }

            // Guardar SIN timestamps
            $quejaSugerencia = new PquejasugerenciaModel();
            $quejaSugerencia->id_pasajero = $pasajero->id_pasajero;
            $quejaSugerencia->quejaSugerencia = $validated['quejaSugerencia'];
            $quejaSugerencia->tipoComentario = $validated['tipoComentario'];
            $quejaSugerencia->areaQS = $validated['areaQS'];
            $quejaSugerencia->save();

            return response()->json([
                'success' => true,
                'message' => 'Tu comentario ha sido enviado exitosamente.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el comentario: ' . $e->getMessage()
            ], 500);
        }
    }
}
