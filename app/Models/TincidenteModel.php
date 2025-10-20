<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TincidenteModel extends Model
{
    protected $table = 'incidente';
    protected $primaryKey = 'id_incidente';
    public $timestamps = false;

    public function obtenerTodosConAsignacion()
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
                a.id_asignacion,
                u.placa,
                o.licencia,
                r.origen,
                r.destino
            FROM incidente i
            LEFT JOIN asignacion a ON i.id_asignacion = a.id_asignacion
            LEFT JOIN unidad u ON a.id_unidad = u.id_unidad
            LEFT JOIN operador o ON a.id_operador = o.id_operator
            LEFT JOIN ruta r ON a.id_ruta = r.id_ruta
            ORDER BY i.fecha DESC, i.hora DESC
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array) $row, $result);
    }

    public function obtenerAsignacionesDisponibles()
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                a.id_asignacion,
                u.placa,
                o.licencia,
                r.origen,
                r.destino
            FROM asignacion a
            LEFT JOIN unidad u ON a.id_unidad = u.id_unidad
            LEFT JOIN operador o ON a.id_operador = o.id_operator
            LEFT JOIN ruta r ON a.id_ruta = r.id_ruta
            ORDER BY a.fecha DESC, a.hora DESC
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array) $row, $result);
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
                i.estado
            FROM incidente i
            WHERE i.id_incidente = ?
        ";

        $result = $db->select($sql, [$id]);
        return count($result) > 0 ? (array)$result[0] : null;
    }
}
