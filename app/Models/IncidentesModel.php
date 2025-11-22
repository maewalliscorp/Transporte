<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IncidentesModel extends Model
{
    protected $table = 'incidente';
    protected $primaryKey = 'id_incidente';
    public $incrementing = true;
    public $timestamps = false;

    public function obtenerIncidentes(): array
    {
        $sql = "
            SELECT
                i.id_incidente,
                i.descripcion,
                i.fecha,
                i.hora,
                i.estado,
                i.solucion,
                i.id_asignacion,
                a.id_asignacion,
                u.placa,
                o.licencia,
                r.origen,
                r.destino
            FROM incidente i
            LEFT JOIN asignacion a ON a.id_asignacion = i.id_asignacion
            LEFT JOIN unidad u ON u.id_unidad = a.id_unidad
            LEFT JOIN operador o ON o.id_operator = a.id_operador
            LEFT JOIN ruta r ON r.id_ruta = a.id_ruta
            ORDER BY i.fecha DESC, i.hora DESC
        ";

        $result = DB::select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerIncidentesPendientes(): array
    {
        $sql = "
            SELECT
                i.id_incidente,
                i.descripcion,
                i.fecha,
                i.hora,
                i.estado,
                i.solucion,
                i.id_asignacion,
                a.id_asignacion,
                u.placa,
                o.licencia,
                r.origen,
                r.destino
            FROM incidente i
            LEFT JOIN asignacion a ON i.id_asignacion = a.id_asignacion
            LEFT JOIN unidad u ON a.id_unidad = u.id_unidad
            LEFT JOIN operador o ON o.id_operator = a.id_operador
            LEFT JOIN ruta r ON a.id_ruta = r.id_ruta
            WHERE i.estado = 'pendiente'
            ORDER BY i.fecha DESC, i.hora DESC
        ";

        $result = DB::select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerAsignacionesActivas(): array
    {
        $sql = "
            SELECT
                a.id_asignacion,
                u.placa,
                o.licencia,
                r.origen,
                r.destino
            FROM asignacion a
            LEFT JOIN unidad u ON a.id_unidad = u.id_unidad
            LEFT JOIN operador o ON o.id_operator = a.id_operador
            LEFT JOIN ruta r ON a.id_ruta = r.id_ruta
            ORDER BY a.fecha DESC
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

    public function crear($datos)
    {
        return DB::table('incidente')->insert($datos);
    }

    public function actualizar($id, $datos)
    {
        return DB::table('incidente')
            ->where('id_incidente', $id)
            ->update($datos);
    }

    public function eliminar($id)
    {
        return DB::table('incidente')
            ->where('id_incidente', $id)
            ->delete();
    }

    public function obtenerPorId($id)
    {
        $sql = "
            SELECT
                i.id_incidente,
                i.id_asignacion,
                i.descripcion,
                i.fecha,
                i.hora,
                i.estado,
                i.solucion,
                u.placa,
                o.licencia,
                r.origen,
                r.destino
            FROM incidente i
            LEFT JOIN asignacion a ON i.id_asignacion = a.id_asignacion
            LEFT JOIN unidad u ON a.id_unidad = u.id_unidad
            LEFT JOIN operador o ON o.id_operator = a.id_operador
            LEFT JOIN ruta r ON a.id_ruta = r.id_ruta
            WHERE i.id_incidente = ?
        ";

        $result = DB::select($sql, [$id]);
        return count($result) > 0 ? (array)$result[0] : null;
    }

    public function agregarSolucion($id, $solucion)
    {
        return DB::table('incidente')
            ->where('id_incidente', $id)
            ->update([
                'solucion' => $solucion,
                'estado' => 'resuelto'
            ]);
    }
}
