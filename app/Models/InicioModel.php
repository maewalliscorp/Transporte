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

        // UNIDADES DISPONIBLES (no asignadas hoy)
        $sqlUnidades = "
            SELECT
                u.id_unidad as id,
                u.placa as descripcion,
                'Unidad de Transporte' as tipo,
                CONCAT(u.placa, ' - ', u.modelo, ' - Capacidad: ', u.capacidad, ' personas') as informacion,
                'Disponible' as estado
            FROM unidad u
            WHERE u.id_unidad NOT IN (
                SELECT id_unidad FROM asignacion WHERE fecha = CURDATE()
            )
        ";

        // OPERADORES DISPONIBLES (no asignados hoy)
        $sqlOperadores = "
            SELECT
                o.id_operator as id,
                o.licencia as descripcion,
                'Operador' as tipo,
                CONCAT('Licencia: ', o.licencia) as informacion,
                'Disponible' as estado
            FROM operador o
            WHERE o.id_operator NOT IN (
                SELECT id_operador FROM asignacion WHERE fecha = CURDATE()
            )
        ";

        // RUTAS DISPONIBLES (no asignadas hoy)
        $sqlRutas = "
            SELECT
                r.id_ruta as id,
                CONCAT(r.origen, ' - ', r.destino) as descripcion,
                'Ruta' as tipo,
                CONCAT(r.origen, ' → ', r.destino, ' (', r.nombre, ')') as informacion,
                'Disponible' as estado
            FROM ruta r
            WHERE r.id_ruta NOT IN (
                SELECT id_ruta FROM asignacion WHERE fecha = CURDATE()
            )
        ";

        // HORARIOS DISPONIBLES (no asignados hoy)
        $sqlHorarios = "
            SELECT
                h.id_horario as id,
                CONCAT(h.horaSalida, ' - ', h.horaLlegada) as descripcion,
                'Horario' as tipo,
                CONCAT('Salida: ', h.horaSalida, ' - Llegada: ', h.horaLlegada) as informacion,
                'Disponible' as estado
            FROM horario h
            WHERE h.id_horario NOT IN (
                SELECT r.id_horario
                FROM ruta r
                INNER JOIN asignacion a ON r.id_ruta = a.id_ruta
                WHERE a.fecha = CURDATE()
            )
        ";

        // UNIR TODOS LOS RECURSOS DISPONIBLES
        $sql = "($sqlUnidades)
                UNION ALL
                ($sqlOperadores)
                UNION ALL
                ($sqlRutas)
                UNION ALL
                ($sqlHorarios)
                ORDER BY tipo, id";

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
            ORDER BY a.id_asignacion DESC
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
