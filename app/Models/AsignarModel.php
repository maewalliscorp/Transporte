<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignarModel extends Model
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
                u.id_unidad,
                u.placa,
                u.modelo,
                u.capacidad
            FROM unidad u
            WHERE u.id_unidad NOT IN (
                SELECT id_unidad FROM asignacion WHERE fecha = CURDATE()
            )
            ORDER BY u.placa
        ";

        // OPERADORES DISPONIBLES (no asignados hoy)
        $sqlOperadores = "
            SELECT
                o.id_operator,
                o.licencia,
                o.telefono,
                o.estado
            FROM operador o
            WHERE o.id_operator NOT IN (
                SELECT id_operador FROM asignacion WHERE fecha = CURDATE()
            )
            ORDER BY o.licencia
        ";

        // RUTAS DISPONIBLES (no asignadas hoy)
        $sqlRutas = "
            SELECT
                r.id_ruta,
                r.nombre,
                r.origen,
                r.destino,
                r.duracion_estimada
            FROM ruta r
            WHERE r.id_ruta NOT IN (
                SELECT id_ruta FROM asignacion WHERE fecha = CURDATE()
            )
            ORDER BY r.origen, r.destino
        ";

        // HORARIOS DISPONIBLES (no asignados hoy)
        $sqlHorarios = "
            SELECT
                h.id_horario,
                h.horaSalida,
                h.horaLlegada,
                h.fecha
            FROM horario h
            WHERE h.id_horario NOT IN (
                SELECT r.id_horario
                FROM ruta r
                INNER JOIN asignacion a ON r.id_ruta = a.id_ruta
                WHERE a.fecha = CURDATE()
            )
            ORDER BY h.horaSalida
        ";

        $unidades = $db->select($sqlUnidades);
        $operadores = $db->select($sqlOperadores);
        $rutas = $db->select($sqlRutas);
        $horarios = $db->select($sqlHorarios);

        return [
            'unidades' => array_map(fn($row) => (array) $row, $unidades),
            'operadores' => array_map(fn($row) => (array) $row, $operadores),
            'rutas' => array_map(fn($row) => (array) $row, $rutas),
            'horarios' => array_map(fn($row) => (array) $row, $horarios)
        ];
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


    public function crearAsignacion(array $datos): bool
    {
        try {
            $db = $this->getConnection();

            $resultado = $db->table('asignacion')->insert([
                'id_unidad' => $datos['id_unidad'],
                'id_operador' => $datos['id_operador'],
                'id_ruta' => $datos['id_ruta'],
                'fecha' => $datos['fecha'],
                'hora' => $datos['hora']
            ]);

            return (bool)$resultado;

        } catch (\Exception $e) {
            \Log::error('Error al crear asignación: ' . $e->getMessage());
            return false;
        }
    }
}
