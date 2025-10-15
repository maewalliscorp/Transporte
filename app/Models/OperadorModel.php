<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperadorModel extends Model
{
    protected $table = 'operador';
    protected $primaryKey = 'id_operator';
    public $timestamps = false;

    public function obtenerTodosConUsuario()
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                o.id_operator,
                o.licencia,
                o.telefono,
                o.estado,
                o.id as user_id,
                u.name as nombre_usuario,
                u.email
            FROM operador o
            LEFT JOIN users u ON o.id = u.id
            ORDER BY o.id_operator
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array) $row, $result);
    }

    public function obtenerUsuariosDisponibles()
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                id,
                name,
                email
            FROM users
            WHERE id NOT IN (SELECT id FROM operador WHERE id IS NOT NULL)
            ORDER BY name
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array) $row, $result);
    }

    public function crear($datos)
    {
        return $this->getConnection()
            ->table('operador')
            ->insert($datos);
    }

    public function actualizar($id, $datos)
    {
        return $this->getConnection()
            ->table('operador')
            ->where('id_operator', $id)
            ->update($datos);
    }

    public function eliminar($id)
    {
        return $this->getConnection()
            ->table('operador')
            ->where('id_operator', $id)
            ->delete();
    }

    public function obtenerPorId($id)
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                o.id_operator,
                o.licencia,
                o.telefono,
                o.estado,
                o.id as user_id,
                u.name as nombre_usuario,
                u.email
            FROM operador o
            LEFT JOIN users u ON o.id = u.id
            WHERE o.id_operator = ?
        ";

        $result = $db->select($sql, [$id]);
        return count($result) > 0 ? (array)$result[0] : null;
    }
}
