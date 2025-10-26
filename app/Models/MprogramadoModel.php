<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MprogramadoModel extends Model
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
                u.modelo
            FROM mantenimiento m
            LEFT JOIN unidad u ON m.id_unidad = u.id_unidad
            LEFT JOIN asignacion a ON u.id_unidad = a.id_unidad
            ORDER BY m.fecha_programada ASC
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

    public function obtenerMantenimientoPorId($id)
    {
        return DB::table('mantenimiento')
            ->where('id_mantenimiento', $id)
            ->first();
    }

    public function crearMantenimientoProgramado($data)
    {
        try {
            return DB::table('mantenimiento')->insert([
                'fecha_programada' => $data['fecha_programada'],
                'motivo' => $data['motivo'],
                'kmActual' => $data['kmActual'],
                'id_unidad' => $data['unidad'],
                'fecha_creacion' => now()
            ]);
        } catch (\Exception $e) {
            return false;
        }
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
}
