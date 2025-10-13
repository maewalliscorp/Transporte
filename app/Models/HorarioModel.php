<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioModel extends Model
{
    protected $table = 'horario';
    protected $primaryKey = 'id_horario';
    public $incrementing = true;
    public $timestamps = false;

    public function obtenerTodos()
    {
        $db = $this->getConnection();

        $sql = "SELECT id_horario, horaSalida, horaLlegada, fecha FROM horario ORDER BY fecha, horaSalida";
        $result = $db->select($sql);

        return array_map(fn($row) => (array) $row, $result);
    }

    // Método alternativo usando Eloquent (más moderno)
    public function obtenerTodosEloquent()
    {
        return $this->orderBy('fecha')
            ->orderBy('horaSalida')
            ->get()
            ->toArray();
    }

    public function crear($datos)
    {
        return $this->getConnection()
            ->table('horario')
            ->insert($datos);
    }

    public function actualizar($id, $datos)
    {
        return $this->getConnection()
            ->table('horario')
            ->where('id_horario', $id)
            ->update($datos);
    }

    public function eliminar($id)
    {
        return $this->getConnection()
            ->table('horario')
            ->where('id_horario', $id)
            ->delete();
    }
}
