<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HorarioModel extends Model
{
    protected $table = 'horario';
    protected $primaryKey = 'id_horario';
    public $timestamps = false;

    public function obtenerTodos()
    {
        $db = DB::connection();

        $sql = "SELECT id_horario, horaSalida, horaLlegada, fecha
                FROM horario
                ORDER BY fecha DESC, horaSalida ASC";
        $result = $db->select($sql);

        return array_map(fn($row) => (array) $row, $result);
    }

    public function obtenerPorId($id)
    {
        $db = DB::connection();

        $sql = "SELECT id_horario, horaSalida, horaLlegada, fecha
                FROM horario
                WHERE id_horario = ?";
        $result = $db->select($sql, [$id]);

        return $result ? (array) $result[0] : null;
    }

    public function crear($datos)
    {
        $db = DB::connection();

        $sql = "INSERT INTO horario (horaSalida, horaLlegada, fecha)
                VALUES (?, ?, ?)";

        return $db->insert($sql, [
            $datos['horaSalida'],
            $datos['horaLlegada'],
            $datos['fecha']
        ]);
    }

    public function actualizar($id, $datos)
    {
        $db = DB::connection();

        $sql = "UPDATE horario
                SET horaSalida = ?, horaLlegada = ?, fecha = ?
                WHERE id_horario = ?";

        return $db->update($sql, [
            $datos['horaSalida'],
            $datos['horaLlegada'],
            $datos['fecha'],
            $id
        ]);
    }

    public function eliminar($id)
    {
        $db = DB::connection();

        $sql = "DELETE FROM horario WHERE id_horario = ?";
        return $db->delete($sql, [$id]);
    }

    // Método para verificar si existe un horario con los mismos datos
    public function existeHorario($horaSalida, $horaLlegada, $fecha, $excluirId = null)
    {
        $db = DB::connection();

        $sql = "SELECT COUNT(*) as count
                FROM horario
                WHERE horaSalida = ?
                AND horaLlegada = ?
                AND fecha = ?";

        $params = [$horaSalida, $horaLlegada, $fecha];

        if ($excluirId) {
            $sql .= " AND id_horario != ?";
            $params[] = $excluirId;
        }

        $result = $db->select($sql, $params);
        return $result[0]->count > 0;
    }
}
