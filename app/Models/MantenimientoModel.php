<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MantenimientoModel extends Model
{
    protected $table = 'mantenimiento';
    protected $primaryKey = 'id_mantenimiento';
    public $incrementing = true;
    public $timestamps = false;

    public function obtenerMantenimientosProgramados(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                m.id_mantenimiento,
                m.fecha_programada,
                m.motivo,
                m.kmActual,
                m.estado,
                u.placa,
                u.modelo,
                o.licencia
            FROM mantenimiento m
            LEFT JOIN unidad u ON m.id_unidad = u.id_unidad
            LEFT JOIN asignacion a ON u.id_unidad = a.id_unidad
            LEFT JOIN operador o ON a.id_operador = o.id_operator
            ORDER BY m.fecha_programada ASC
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerMantenimientosRealizados(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                m.id_mantenimiento,
                m.fecha_programada as fecha,
                m.motivo as descripcion,
                m.kmActual,
                m.estado,
                u.placa,
                u.modelo,
                o.licencia
            FROM mantenimiento m
            LEFT JOIN unidad u ON m.id_unidad = u.id_unidad
            LEFT JOIN asignacion a ON u.id_unidad = a.id_unidad
            LEFT JOIN operador o ON a.id_operador = o.id_operator
       /*WHERE m.estado = 'completado' */
            ORDER BY m.fecha_programada DESC
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerAlertasMantenimiento(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                a.id_alertaMantenimiento,
                a.fechaUltimoMantenimiento,
                a.kmProxMantenimiento,
                a.fechaProxMantenimiento,
                a.incidenteReportado,
                a.estadoAlerta,
                u.placa,
                u.modelo,
                m.kmActual
            FROM alertamantenimiento a
            LEFT JOIN mantenimiento m ON a.id_mantenimiento = m.id_mantenimiento
            LEFT JOIN unidad u ON m.id_unidad = u.id_unidad
            ORDER BY a.fechaProxMantenimiento ASC
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerHistorialMantenimiento(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
                m.id_mantenimiento,
                m.fecha_programada as fecha,
                m.motivo as descripcion,
                m.kmActual,
                m.estado,
                u.placa,
                u.modelo
            FROM mantenimiento m
            LEFT JOIN unidad u ON m.id_unidad = u.id_unidad
            /*WHERE m.estado IN ('completado', 'cancelado') */
            ORDER BY m.fecha_programada DESC
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerUnidades(): array
    {
        $db = $this->getConnection();

        $sql = "SELECT id_unidad, placa, modelo FROM unidad ORDER BY placa";
        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerOperadores(): array
    {
        $db = $this->getConnection();

        $sql = "SELECT id_operator, licencia FROM operador ORDER BY licencia";
        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }
}
