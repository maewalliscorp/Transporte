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
        return $this->getConnection()
            ->table('unidad')
            ->orderBy('id_unidad')
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
