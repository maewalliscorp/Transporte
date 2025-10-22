<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UnidadModel extends Model
{
    protected $table = 'unidad';
    protected $primaryKey = 'id_unidad';
    public $timestamps = false;

    public function obtenerTodos()
    {
        $db = DB::connection();

        $sql = "SELECT id_unidad, placa, modelo, capacidad
                FROM unidad
                ORDER BY id_unidad ASC";
        $result = $db->select($sql);

        return array_map(fn($row) => (array) $row, $result);
    }

    public function obtenerPorId($id)
    {
        $db = DB::connection();

        $sql = "SELECT id_unidad, placa, modelo, capacidad
                FROM unidad
                WHERE id_unidad = ?";
        $result = $db->select($sql, [$id]);

        return $result ? (array) $result[0] : null;
    }

    public function crear($datos)
    {
        $db = DB::connection();

        $sql = "INSERT INTO unidad (placa, modelo, capacidad)
                VALUES (?, ?, ?)";

        return $db->insert($sql, [
            $datos['placa'],
            $datos['modelo'],
            $datos['capacidad']
        ]);
    }

    public function actualizar($id, $datos)
    {
        $db = DB::connection();

        $sql = "UPDATE unidad
                SET placa = ?, modelo = ?, capacidad = ?
                WHERE id_unidad = ?";

        return $db->update($sql, [
            $datos['placa'],
            $datos['modelo'],
            $datos['capacidad'],
            $id
        ]);
    }

    public function eliminar($id)
    {
        $db = DB::connection();

        $sql = "DELETE FROM unidad WHERE id_unidad = ?";
        return $db->delete($sql, [$id]);
    }

    // Método para verificar si existe una unidad con la misma placa
    public function existePlaca($placa, $excluirId = null)
    {
        $db = DB::connection();

        $sql = "SELECT COUNT(*) as count
                FROM unidad
                WHERE placa = ?";

        $params = [$placa];

        if ($excluirId) {
            $sql .= " AND id_unidad != ?";
            $params[] = $excluirId;
        }

        $result = $db->select($sql, $params);
        return $result[0]->count > 0;
    }
}
