<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoreoModel extends Model
{
    protected $table = 'unidades'; // o el nombre de tu tabla de unidades
    protected $primaryKey = 'id_unidad';
    public $incrementing = true;
    public $timestamps = false;

    public function obtenerUnidadesConGPS()
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                u.id_unidad,
                u.placa,
                u.modelo,
                u.capacidad,
                u.latitud,
                u.longitud,
                u.estado_gps,
                o.nombre as operador_nombre,
                o.licencia,
                r.origen,
                r.destino,
                h.horaSalida,
                h.horaLlegada
            FROM unidad u
            LEFT JOIN operador o ON u.id_operador_actual = o.id_operator
            LEFT JOIN ruta r ON u.id_ruta_actual = r.id_ruta
            LEFT JOIN horario h ON r.id_horario = h.id_horario
            WHERE u.estado_gps = 'activo'
            ORDER BY u.placa
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerHistorialRuta($id_unidad, $fecha = null)
    {
        $db = $this->getConnection();

        if (!$fecha) {
            $fecha = date('Y-m-d');
        }

        $sql = "
            SELECT
                latitud,
                longitud,
                velocidad,
                timestamp,
                evento
            FROM historial_gps
            WHERE id_unidad = ? AND DATE(timestamp) = ?
            ORDER BY timestamp ASC
        ";

        $result = $db->select($sql, [$id_unidad, $fecha]);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerUnidadesParaPuntualidad()
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                u.id_unidad,
                u.placa,
                u.modelo,
                o.nombre as operador_nombre,
                r.origen,
                r.destino,
                h.horaSalida,
                h.horaLlegada,
                a.estado_puntualidad,
                a.tiempo_retardo
            FROM unidad u
            LEFT JOIN operador o ON u.id_operador_actual = o.id_operator
            LEFT JOIN ruta r ON u.id_ruta_actual = r.id_ruta
            LEFT JOIN horario h ON r.id_horario = h.id_horario
            LEFT JOIN asignacion a ON u.id_unidad = a.id_unidad AND a.fecha = CURDATE()
            WHERE u.estado = 'activa'
            ORDER BY u.placa
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }
}
