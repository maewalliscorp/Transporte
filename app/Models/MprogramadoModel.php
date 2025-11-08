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
                m.tipo_mantenimiento,
                m.costo,
                m.pieza,
                m.kmActual,
                m.estado,
                u.placa,
                u.modelo
            FROM mantenimiento m
            LEFT JOIN unidad u ON m.id_unidad = u.id_unidad
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
        $mantenimiento = DB::table('mantenimiento')
            ->where('id_mantenimiento', $id)
            ->first();

        if ($mantenimiento) {
            return [
                'success' => true,
                'data' => (array)$mantenimiento
            ];
        }

        return [
            'success' => false,
            'message' => 'Mantenimiento no encontrado'
        ];
    }

    public function crearMantenimientoProgramado($data)
    {
        try {
            $insertData = [
                'fecha_programada' => $data['fecha_programada'],
                'motivo' => $data['motivo'],
                'tipo_mantenimiento' => $data['tipo_mantenimiento'],
                'kmActual' => $data['kmActual'],
                'id_unidad' => $data['unidad'],
                'estado' => $data['estado']
            ];

            // Agregar campos opcionales si existen
            if (isset($data['pieza']) && !empty($data['pieza'])) {
                $insertData['pieza'] = $data['pieza'];
            }

            if (isset($data['costo']) && $data['costo'] > 0) {
                $insertData['costo'] = $data['costo'];
            }

            $result = DB::table('mantenimiento')->insert($insertData);

            return $result ? [
                'success' => true,
                'message' => 'Mantenimiento programado correctamente'
            ] : [
                'success' => false,
                'message' => 'Error al programar el mantenimiento'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error en la base de datos: ' . $e->getMessage()
            ];
        }
    }

    public function actualizarMantenimiento($id, $data)
    {
        try {
            $updateData = [
                'fecha_programada' => $data['fecha_programada'],
                'motivo' => $data['motivo'],
                'tipo_mantenimiento' => $data['tipo_mantenimiento'],
                'kmActual' => $data['kmActual'],
                'id_unidad' => $data['unidad'],
                'estado' => $data['estado']
            ];

            // Agregar campos opcionales si existen
            if (isset($data['pieza'])) {
                $updateData['pieza'] = $data['pieza'];
            }

            if (isset($data['costo'])) {
                $updateData['costo'] = $data['costo'];
            }

            $result = DB::table('mantenimiento')
                ->where('id_mantenimiento', $id)
                ->update($updateData);

            return $result ? [
                'success' => true,
                'message' => 'Mantenimiento actualizado correctamente'
            ] : [
                'success' => false,
                'message' => 'Error al actualizar el mantenimiento'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error en la base de datos: ' . $e->getMessage()
            ];
        }
    }

    public function eliminarMantenimiento($id)
    {
        try {
            // Primero: Verificar si existen alertas asociadas
            $alertasExisten = DB::table('alertamantenimiento')
                ->where('id_mantenimiento', $id)
                ->exists();

            if ($alertasExisten) {
                return [
                    'success' => false,
                    'message' => 'No se puede eliminar el mantenimiento porque cuenta con alertas asociadas'
                ];
            }

            // Si no hay alertas asociadas, proceder con la eliminación
            $result = DB::table('mantenimiento')
                ->where('id_mantenimiento', $id)
                ->delete();

            return $result ? [
                'success' => true,
                'message' => 'Mantenimiento eliminado correctamente'
            ] : [
                'success' => false,
                'message' => 'Error al eliminar el mantenimiento'
            ];

        } catch (\Exception $e) {
            // Capturar el error de restricción de clave foránea
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                return [
                    'success' => false,
                    'message' => 'No se puede eliminar el mantenimiento porque cuenta con registros relacionados en otras tablas'
                ];
            }

            return [
                'success' => false,
                'message' => 'Error en la base de datos: ' . $e->getMessage()
            ];
        }
    }
}
