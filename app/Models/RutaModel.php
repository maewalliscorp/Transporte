<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RutaModel extends Model
{
    protected $table = 'ruta';
    protected $primaryKey = 'id_ruta';
    public $timestamps = false;

    public function obtenerTodosConHorario()
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                r.id_ruta,
                r.id_horario,
                r.nombre,
                r.origen,
                r.destino,
                r.duracion_estimada,
                h.horaSalida,
                h.horaLlegada
            FROM ruta r
            LEFT JOIN horario h ON r.id_horario = h.id_horario
            ORDER BY r.id_ruta
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array) $row, $result);
    }

    public function obtenerHorariosDisponibles()
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                id_horario,
                horaSalida,
                horaLlegada,
                fecha
            FROM horario
            ORDER BY fecha, horaSalida
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array) $row, $result);
    }

    public function crear($datos)
    {
        return $this->getConnection()
            ->table('ruta')
            ->insert($datos);
    }

    public function actualizar($id, $datos)
    {
        return $this->getConnection()
            ->table('ruta')
            ->where('id_ruta', $id)
            ->update($datos);
    }

    public function eliminar($id)
    {
        return $this->getConnection()
            ->table('ruta')
            ->where('id_ruta', $id)
            ->delete();
    }

    public function obtenerPorId($id)
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                r.id_ruta,
                r.id_horario,
                r.nombre,
                r.origen,
                r.destino,
                r.duracion_estimada,
                h.horaSalida,
                h.horaLlegada
            FROM ruta r
            LEFT JOIN horario h ON r.id_horario = h.id_horario
            WHERE r.id_ruta = ?
        ";

        $result = $db->select($sql, [$id]);
        return count($result) > 0 ? (array)$result[0] : null;
    }
}
