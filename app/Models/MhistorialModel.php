<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MhistorialModel extends Model
{
    protected $table = 'mantenimiento';
    protected $primaryKey = 'id_mantenimiento';
    public $incrementing = true;
    public $timestamps = false;

    public function obtenerHistorialMantenimiento(): array
    {
        $sql = "
            SELECT
                m.id_mantenimiento,
                m.fecha_programada as fecha,
                m.motivo as descripcion,
                m.kmActual,
                m.estado,
                u.placa,
                u.modelo
            FROM mantenimiento m
            LEFT JOIN unidad u ON m.id_unidad = u.id_unidad
            ORDER BY m.fecha_programada DESC
        ";

        $result = DB::select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerUnidades(): array
    {
        $sql = "SELECT id_unidad, placa, modelo FROM unidad ORDER BY placa";
        $result = DB::select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function contarTotalMantenimientos(): int
    {
        return DB::table('mantenimiento')->count();
    }

    public function contarMantenimientosPorTipo($tipo): int
    {
        // Por ahora retornamos 0 hasta que implementes el campo tipo_mantenimiento
        return 0;
    }

    public function obtenerCostoTotal()
    {
        // Por ahora retornamos 0 hasta que implementes el campo costo
        return 0;
    }
}
