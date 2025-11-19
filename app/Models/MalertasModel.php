<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MalertasModel extends Model
{
    protected $table = 'alertamantenimiento';
    protected $primaryKey = 'id_alertaMantenimiento';
    public $incrementing = true;
    public $timestamps = false;

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

    public function obtenerUnidades(): array
    {
        $sql = "
            SELECT
                u.id_unidad,
                u.placa,
                u.modelo,
                m.id_mantenimiento,
                COALESCE(m.kmActual, 0) as kmActual
            FROM mantenimiento m
            INNER JOIN unidad u ON m.id_unidad = u.id_unidad
            ORDER BY u.placa, m.id_mantenimiento DESC
        ";

        $result = DB::select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function contarAlertasPorEstado($estado): int
    {
        return DB::table('alertamantenimiento')
            ->where('estadoAlerta', $estado)
            ->count();
    }

    public function obtenerAlertaPorId($id)
    {
        $sql = "
            SELECT
                a.id_alertaMantenimiento,
                a.id_mantenimiento,
                a.fechaUltimoMantenimiento,
                a.kmProxMantenimiento,
                a.fechaProxMantenimiento,
                a.incidenteReportado,
                a.estadoAlerta,
                u.id_unidad,
                m.kmActual
            FROM alertamantenimiento a
            LEFT JOIN mantenimiento m ON a.id_mantenimiento = m.id_mantenimiento
            LEFT JOIN unidad u ON m.id_unidad = u.id_unidad
            WHERE a.id_alertaMantenimiento = ?
        ";

        $result = DB::select($sql, [$id]);
        return $result[0] ?? null;
    }

    public function crearAlerta($data)
    {
        try {
            \Log::info('Datos recibidos en crearAlerta:', $data);

            // Insertar directamente con el id_mantenimiento recibido
            $resultado = DB::table('alertamantenimiento')->insert([
                'id_mantenimiento' => $data['id_mantenimiento'],
                'fechaUltimoMantenimiento' => $data['fechaUltimoMantenimiento'],
                'kmProxMantenimiento' => $data['kmProxMantenimiento'],
                'fechaProxMantenimiento' => $data['fechaProxMantenimiento'],
                'incidenteReportado' => $data['incidenteReportado'] ?? null,
                'estadoAlerta' => $data['estadoAlerta']
            ]);

            \Log::info('Resultado de la inserción: ' . ($resultado ? 'true' : 'false'));

            return $resultado;

        } catch (\Exception $e) {
            \Log::error('Error al crear alerta: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    public function actualizarAlerta($id, $data)
    {
        try {
            \Log::info('Datos recibidos en actualizarAlerta:', $data);

            // Usar directamente el id_mantenimiento recibido
            $resultado = DB::table('alertamantenimiento')
                ->where('id_alertaMantenimiento', $id)
                ->update([
                    'id_mantenimiento' => $data['id_mantenimiento'],
                    'fechaUltimoMantenimiento' => $data['fechaUltimoMantenimiento'],
                    'kmProxMantenimiento' => $data['kmProxMantenimiento'],
                    'fechaProxMantenimiento' => $data['fechaProxMantenimiento'],
                    'incidenteReportado' => $data['incidenteReportado'] ?? null,
                    'estadoAlerta' => $data['estadoAlerta']
                ]);

            \Log::info('Resultado de la actualización: ' . ($resultado ? 'true' : 'false'));

            return $resultado;

        } catch (\Exception $e) {
            \Log::error('Error al actualizar alerta: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
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
            \Log::error('Error al eliminar alerta: ' . $e->getMessage());
            return false;
        }
    }
}
