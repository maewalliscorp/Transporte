<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RutaModel extends Model
{
    protected $table = 'ruta';
    protected $primaryKey = 'id_ruta';
    public $timestamps = false;

    public function obtenerTodosConHorario()
    {
        $db = DB::connection();

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
        $db = DB::connection();

        $sql = "
            SELECT
                id_horario,
                horaSalida,
                horaLlegada,
                fecha
            FROM horario
            WHERE id_horario NOT IN (
                SELECT DISTINCT id_horario
                FROM ruta
                WHERE id_horario IS NOT NULL
            )
            ORDER BY fecha, horaSalida
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array) $row, $result);
    }

    public function obtenerPorId($id)
    {
        $db = DB::connection();

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
        return $result ? (array) $result[0] : null;
    }

    public function crear($datos)
    {
        $db = DB::connection();

        $sql = "INSERT INTO ruta (nombre, origen, destino, duracion_estimada, id_horario)
                VALUES (?, ?, ?, ?, ?)";

        return $db->insert($sql, [
            $datos['nombre'],
            $datos['origen'],
            $datos['destino'],
            $datos['duracion_estimada'],
            $datos['id_horario']
        ]);
    }

    public function actualizar($id, $datos)
    {
        $db = DB::connection();

        $sql = "UPDATE ruta
                SET nombre = ?, origen = ?, destino = ?, duracion_estimada = ?, id_horario = ?
                WHERE id_ruta = ?";

        return $db->update($sql, [
            $datos['nombre'],
            $datos['origen'],
            $datos['destino'],
            $datos['duracion_estimada'],
            $datos['id_horario'],
            $id
        ]);
    }

    public function eliminar($id)
    {
        $db = DB::connection();

        $sql = "DELETE FROM ruta WHERE id_ruta = ?";
        return $db->delete($sql, [$id]);
    }

    // Método para verificar si ya existe una ruta con el mismo nombre
    public function existeRuta($nombre, $excluirId = null)
    {
        $db = DB::connection();

        $sql = "SELECT COUNT(*) as count
                FROM ruta
                WHERE nombre = ?";

        $params = [$nombre];

        if ($excluirId) {
            $sql .= " AND id_ruta != ?";
            $params[] = $excluirId;
        }

        $result = $db->select($sql, $params);
        return $result[0]->count > 0;
    }

    // Método para verificar si el horario ya está asignado a otra ruta
    public function horarioOcupado($idHorario, $excluirId = null)
    {
        $db = DB::connection();

        $sql = "SELECT COUNT(*) as count
                FROM ruta
                WHERE id_horario = ?";

        $params = [$idHorario];

        if ($excluirId) {
            $sql .= " AND id_ruta != ?";
            $params[] = $excluirId;
        }

        $result = $db->select($sql, $params);
        return $result[0]->count > 0;
    }
}
