<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PregistroModel;
use Illuminate\Support\Facades\Log;

class PregistroController extends Controller
{
    public function index()
    {
        return view('pasajero.p-registro');
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'correo' => 'required|email|unique:users,email',
                'contrasena' => 'required|min:8|confirmed',
                'tipoPasajero' => 'required|in:estudiante,adulto_mayor,normal',
                'idTarjeta' => 'required|string|max:255'
            ], [
                'correo.unique' => 'Este correo electrónico ya está registrado.',
                'contrasena.confirmed' => 'Las contraseñas no coinciden.',
                'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.'
            ]);

            // Usar el modelo para crear usuario y pasajero
            PregistroModel::crearUsuarioYPasajero([
                'nombre' => $validated['nombre'],
                'correo' => $validated['correo'],
                'contrasena' => $validated['contrasena'],
                'tipoPasajero' => $validated['tipoPasajero'],
                'idTarjeta' => $validated['idTarjeta']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pasajero registrado exitosamente'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error al registrar pasajero: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el pasajero. Por favor, intente nuevamente.'
            ], 500);
        }
    }
}
