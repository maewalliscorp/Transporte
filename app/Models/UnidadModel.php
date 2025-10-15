<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadModel extends Model
{
    protected $table = 'unidad';
    protected $primaryKey = 'id_unidad';
    public $timestamps = false;

    public function obtenerTodos()
    {
        $db = $this->getConnection();

        $sql = "SELECT id_unidad, placa, modelo, capacidad FROM unidad ORDER BY id_unidad";
        $result = $db->select($sql);

        return array_map(fn($row) => (array) $row, $result);
    }

    // Método alternativo usando Eloquent
    public function obtenerTodosEloquent()
    {
        return $this->orderBy('id_unidad')
            ->get()
            ->toArray();
    }

    public function crear($datos)
    {
        return $this->getConnection()
            ->table('unidad')
            ->insert($datos);
    }

    public function actualizar($id, $datos)
    {
        return $this->getConnection()
            ->table('unidad')
            ->where('id_unidad', $id)
            ->update($datos);
    }

    public function eliminar($id)
    {
        return $this->getConnection()
            ->table('unidad')
            ->where('id_unidad', $id)
            ->delete();
    }
}
