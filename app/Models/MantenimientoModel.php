<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MantenimientoModel extends Model
{
    protected $table = 'mantenimiento';
    protected $primaryKey = 'id_mantenimiento';
    public $incrementing = true;
    public $timestamps = false;

    public function obtenerMantenimientosProgramados(): array
    {
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

        $result = DB::select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerMantenimientosRealizados(): array
    {
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
            ORDER BY m.fecha_programada DESC
        ";

        $result = DB::select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerAlertasMantenimiento(): array
    {
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

        $result = DB::select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerHistorialMantenimiento(): array
    {
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
            ORDER BY m.fecha_programada DESC
        ";

        $result = DB::select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerUnidades(): array
    {
        $sql = "SELECT id_unidad, placa, modelo FROM unidad ORDER BY placa";
        $result = DB::select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerOperadores(): array
    {
        $sql = "SELECT id_operator, licencia FROM operador ORDER BY licencia";
        $result = DB::select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    // Nuevos métodos para CRUD
    public function obtenerMantenimientoPorId($id)
    {
        return DB::table('mantenimiento')
            ->where('id_mantenimiento', $id)
            ->first();
    }

    public function actualizarMantenimiento($id, $data)
    {
        try {
            return DB::table('mantenimiento')
                ->where('id_mantenimiento', $id)
                ->update([
                    'fecha_programada' => $data['fecha_programada'],
                    'motivo' => $data['motivo'],
                    'kmActual' => $data['kmActual'],
                    'estado' => $data['estado'] ?? 'programado',
                    'id_unidad' => $data['unidad']
                ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function eliminarMantenimiento($id)
    {
        try {
            return DB::table('mantenimiento')
                ->where('id_mantenimiento', $id)
                ->delete();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function obtenerAlertaPorId($id)
    {
        return DB::table('alertamantenimiento')
            ->where('id_alertaMantenimiento', $id)
            ->first();
    }

    public function actualizarAlerta($id, $data)
    {
        try {
            return DB::table('alertamantenimiento')
                ->where('id_alertaMantenimiento', $id)
                ->update([
                    'fechaUltimoMantenimiento' => $data['fechaUltimoMantenimiento'],
                    'kmProxMantenimiento' => $data['kmProxMantenimiento'],
                    'fechaProxMantenimiento' => $data['fechaProxMantenimiento'],
                    'incidenteReportado' => $data['incidenteReportado'],
                    'estadoAlerta' => $data['estadoAlerta']
                ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function eliminarAlerta($id)
    {
        try {
            return DB::table('alertamantenimiento')
                ->where('id_alertaMantenimiento', $id)
                ->delete();
        } catch (\Exception $e) {
            return false;
        }
    }
}
