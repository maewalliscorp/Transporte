<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PhistorialModel extends Model
{
    protected $table = 'viaje';
    protected $primaryKey = 'id_viaje';
    public $incrementing = true;
    public $timestamps = false;

    public function obtenerHistorialViajes($idPasajero): array
    {
        $sql = "
            SELECT
                v.id_viaje,
                v.fecha as fecha_viaje,
                u.placa as unidad,
                r.nombre as ruta,
                a.hora,
                p.tarifaPasajero as monto,
                'Efectivo' as medio_pago,
                r.origen,
                r.destino
            FROM viaje v
            INNER JOIN pasajero p ON v.id_pasajero = p.id_pasajero
            INNER JOIN asignacion a ON v.id_asignacion = a.id_asignacion
            INNER JOIN unidad u ON a.id_unidad = u.id_unidad
            INNER JOIN ruta r ON a.id_ruta = r.id_ruta
            WHERE p.id_pasajero = ?
            ORDER BY v.fecha DESC, a.hora DESC
        ";

        $result = DB::select($sql, [$idPasajero]);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerRutas(): array
    {
        $sql = "
            SELECT DISTINCT r.nombre
            FROM ruta r
            INNER JOIN asignacion a ON r.id_ruta = a.id_ruta
            INNER JOIN viaje v ON a.id_asignacion = v.id_asignacion
            ORDER BY r.nombre
        ";

        $result = DB::select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerIdPasajeroPorUsuario($userId): ?int
    {
        $pasajero = DB::table('pasajero')
            ->where('id', $userId)
            ->first();

        return $pasajero ? $pasajero->id_pasajero : null;
    }
}

