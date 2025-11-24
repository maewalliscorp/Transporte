<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PregistroModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PregistroController extends Controller
{
    public function index()
    {
        return view('pasajero.p-registro');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            Log::info('=== INICIANDO REGISTRO DE PASAJERO ===');
            Log::info('Datos recibidos:', $request->all());

            // Validar los datos
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'correo' => 'required|email|unique:users,email',
                'contrasena' => 'required|min:8|confirmed',
                'tipoPasajero' => 'required|in:estudiante,adulto_mayor,normal',
                'idTarjeta' => 'required|string|max:255'
            ], [
                'nombre.required' => 'El nombre completo es obligatorio.',
                'correo.required' => 'El correo electrónico es obligatorio.',
                'correo.email' => 'El correo electrónico debe ser válido.',
                'correo.unique' => 'Este correo electrónico ya está registrado.',
                'contrasena.required' => 'La contraseña es obligatoria.',
                'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'contrasena.confirmed' => 'Las contraseñas no coinciden.',
                'tipoPasajero.required' => 'Debe seleccionar un tipo de pasajero.',
                'tipoPasajero.in' => 'El tipo de pasajero seleccionado no es válido.',
                'idTarjeta.required' => 'El ID de tarjeta es obligatorio.'
            ]);

            Log::info('Datos validados correctamente:', $validated);

            // Verificar que tipoPasajero no esté vacío
            if (empty($validated['tipoPasajero'])) {
                throw new \Exception('El tipo de pasajero no puede estar vacío');
            }

            // Usar el modelo para crear usuario y pasajero
            Log::info('Creando usuario y pasajero...');
            $resultado = PregistroModel::crearUsuarioYPasajero([
                'nombre' => $validated['nombre'],
                'correo' => $validated['correo'],
                'contrasena' => $validated['contrasena'],
                'tipoPasajero' => $validated['tipoPasajero'],
                'idTarjeta' => $validated['idTarjeta']
            ]);

            Log::info('Usuario creado con ID: ' . $resultado->id);

            DB::commit();
            Log::info('=== REGISTRO EXITOSO ===');

            return response()->json([
                'success' => true,
                'message' => 'Pasajero registrado exitosamente'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ERROR COMPLETO AL REGISTRAR PASAJERO:');
            Log::error('Mensaje: ' . $e->getMessage());
            Log::error('Archivo: ' . $e->getFile());
            Log::error('Línea: ' . $e->getLine());

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el pasajero: ' . $e->getMessage()
            ], 500);
        }
    }
}
