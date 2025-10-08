<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InicioModel extends Model
{
    protected $table = 'asignacion';
    protected $primaryKey = 'id_asignacion';
    public $incrementing = true;
    public $timestamps = false;

    public function obtenerDisponibles(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                u.id_unidad, u.placa, u.modelo, u.capacidad,
                o.licencia,

                r.id_ruta, r.nombre, r.origen, r.destino,
                h.horaSalida, h.horaLlegada
            FROM unidad u
            LEFT JOIN (
                SELECT a1.*
                FROM asignacion a1
                LEFT JOIN asignacion a2
                    ON a1.id_unidad = a2.id_unidad
                    AND a1.id_asignacion < a2.id_asignacion
                WHERE a2.id_asignacion IS NULL
            ) ult_asign ON ult_asign.id_unidad = u.id_unidad
            LEFT JOIN operador o ON ult_asign.id_operador = o.id_operator
            LEFT JOIN ruta r ON ult_asign.id_ruta = r.id_ruta
            LEFT JOIN horario h ON r.id_horario = h.id_horario
            ORDER BY u.id_unidad
        ";

        $result = $db->select($sql);

        return array_map(fn($row) => (array) $row, $result);
    }


    public function obtenerAsignados(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                a.id_asignacion,
                u.id_unidad, u.placa, u.modelo, u.capacidad,
                o.id_operator, o.licencia,
                r.id_ruta, r.nombre, r.origen, r.destino,
                h.id_horario, h.horaSalida, h.horaLlegada,
                a.fecha, a.hora
            FROM asignacion a
            LEFT JOIN unidad u ON a.id_unidad = u.id_unidad
            LEFT JOIN operador o ON a.id_operador = o.id_operator
            LEFT JOIN ruta r ON a.id_ruta = r.id_ruta
            LEFT JOIN horario h ON r.id_horario = h.id_horario
            ORDER BY a.id_asignacion
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerUnidades(): array
    {
        $db = $this->getConnection();

        $sql = "SELECT id_unidad, placa, modelo, capacidad FROM unidad ORDER BY id_unidad";
        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerOperadores(): array
    {
        $db = $this->getConnection();

        $sql = "SELECT id_operator, licencia FROM operador ORDER BY id_operator";
        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerRutas(): array
    {
        $db = $this->getConnection();

        $sql = "SELECT id_ruta, nombre, origen, destino FROM ruta ORDER BY id_ruta";
        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerHorarios(): array
    {
        $db = $this->getConnection();

        $sql = "SELECT id_horario, horaSalida, horaLlegada FROM horario ORDER BY id_horario";
        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

}
