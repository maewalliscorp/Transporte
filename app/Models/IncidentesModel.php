<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentesModel extends Model
{
    protected $table = 'incidente';
    protected $primaryKey = 'id_incidente';
    public $incrementing = true;
    public $timestamps = false;

    public function obtenerIncidentes(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                i.id_incidente, i.descripcion, i.fecha, i.hora, i.estado, i.solucion,
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

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerIncidentesPendientes(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                i.id_incidente, i.descripcion, i.fecha, i.hora, i.estado, i.solucion,
                a.id_asignacion,
                u.placa,
                o.licencia,
                r.origen, r.destino
            FROM incidente i
            LEFT JOIN asignacion a ON i.id_asignacion = a.id_asignacion
            LEFT JOIN unidad u ON a.id_unidad = u.id_unidad
            LEFT JOIN operador o ON o.id_operator = a.id_operador
            LEFT JOIN ruta r ON a.id_ruta = r.id_ruta
            WHERE i.estado = 'pendiente'
            ORDER BY i.fecha DESC, i.hora DESC
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerAsignacionesActivas(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                a.id_asignacion,
                u.placa,
                o.licencia,
                r.origen, r.destino
            FROM asignacion a
            LEFT JOIN unidad u ON a.id_unidad = u.id_unidad
            LEFT JOIN operador o ON o.id_operator = a.id_operador
            LEFT JOIN ruta r ON a.id_ruta = r.id_ruta
            ORDER BY a.fecha DESC
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerUnidades(): array
    {
        $db = $this->getConnection();

        $sql = "SELECT id_unidad, placa, modelo FROM unidad ORDER BY placa";
        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function crear($datos)
    {
        return $this->getConnection()
            ->table('incidente')
            ->insert($datos);
    }

    public function actualizar($id, $datos)
    {
        return $this->getConnection()
            ->table('incidente')
            ->where('id_incidente', $id)
            ->update($datos);
    }

    public function eliminar($id)
    {
        return $this->getConnection()
            ->table('incidente')
            ->where('id_incidente', $id)
            ->delete();
    }

    public function obtenerPorId($id)
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                i.id_incidente,
                i.id_asignacion,
                i.descripcion,
                i.fecha,
                i.hora,
                i.estado,
                i.solucion
            FROM incidente i
            WHERE i.id_incidente = ?
        ";

        $result = $db->select($sql, [$id]);
        return count($result) > 0 ? (array)$result[0] : null;
    }

    public function agregarSolucion($id, $solucion)
    {
        return $this->getConnection()
            ->table('incidente')
            ->where('id_incidente', $id)
            ->update([
                'solucion' => $solucion,
                'estado' => 'resuelto'
            ]);
    }
}
