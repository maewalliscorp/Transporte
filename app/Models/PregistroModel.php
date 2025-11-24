<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class PregistroModel extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'idConcesionariaFk'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Crear un nuevo usuario y pasajero
     */
    public static function crearUsuarioYPasajero($datos)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($datos) {
            // Crear usuario
            $usuario = self::create([
                'name' => $datos['nombre'],
                'email' => $datos['correo'],
                'password' => Hash::make($datos['contrasena']),
                'idConcesionariaFk' => 0,
                'email_verified_at' => now(),
            ]);

            // Determinar tarifa
            $tarifa = self::determinarTarifa($datos['tipoPasajero']);

            // Crear pasajero
            \Illuminate\Support\Facades\DB::table('pasajero')->insert([
                'tarifaPasajero' => $tarifa,
                'id' => $usuario->id,
                'numtarjeta' => $datos['idTarjeta'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $usuario;
        });
    }

    /**
     * Determinar tarifa según tipo de pasajero
     */
    private static function determinarTarifa($tipoPasajero)
    {
        switch ($tipoPasajero) {
            case 'estudiante':
                return 5.00;
            case 'adulto_mayor':
                return 3.00;
            case 'normal':
            default:
                return 10.00;
        }
    }

    /**
     * Verificar si email ya existe
     */
    public static function emailExiste($email)
    {
        return self::where('email', $email)->exists();
    }
}
