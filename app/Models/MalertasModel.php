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
        $sql = "SELECT id_unidad, placa, modelo FROM unidad ORDER BY placa";
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
        return DB::table('alertamantenimiento')
            ->where('id_alertaMantenimiento', $id)
            ->first();
    }

    public function crearAlerta($data)
    {
        try {
            return DB::table('alertamantenimiento')->insert([
                'fechaUltimoMantenimiento' => $data['fechaUltimoMantenimiento'],
                'kmProxMantenimiento' => $data['kmProxMantenimiento'],
                'fechaProxMantenimiento' => $data['fechaProxMantenimiento'],
                'incidenteReportado' => $data['incidenteReportado'],
                'estadoAlerta' => $data['estadoAlerta'],
                'id_mantenimiento' => 1, // Ajustar según tu lógica
                'fecha_creacion' => now()
            ]);
        } catch (\Exception $e) {
            return false;
        }
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
