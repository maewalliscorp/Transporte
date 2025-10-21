<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanzasModel extends Model
{
    protected $table = 'movimientobancario';
    protected $primaryKey = 'id_movimiento';
    public $timestamps = false;

    public function obtenerIngresos(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT m.id_movimiento, m.tipoMovimiento, m.monto, m.fecha, m.hora,
                   m.concepto, c.id_contador, u.name AS contador
            FROM movimientobancario m
            LEFT JOIN contador c ON m.contadorFK = c.id_contador
            LEFT JOIN users u ON c.id = u.id
            WHERE m.tipoMovimiento = 'ingreso'
            ORDER BY m.fecha DESC, m.hora DESC
        ";

        $result = $db->select($sql);
        return array_map(fn($r) => (array)$r, $result);
    }

    public function obtenerEgresos(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT m.id_movimiento, m.tipoMovimiento, m.monto, m.fecha, m.hora,
                   m.concepto, c.id_contador, u.name AS contador
            FROM movimientobancario m
            LEFT JOIN contador c ON m.contadorFK = c.id_contador
            LEFT JOIN users u ON c.id = u.id
            WHERE m.tipoMovimiento = 'egreso'
            ORDER BY m.fecha DESC, m.hora DESC
        ";

        $result = $db->select($sql);
        return array_map(fn($r) => (array)$r, $result);
    }

    public function obtenerTarifas(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT t.id_tarifa, r.nombre AS ruta, t.tarifaBaseRuta, t.tipoPasajero,
                   t.descuentoPasajero, t.tarifaFinal, t.notas
            FROM tarifa t
            LEFT JOIN ruta r ON t.id_ruta = r.id_ruta
            ORDER BY r.nombre
        ";

        $result = $db->select($sql);
        return array_map(fn($r) => (array)$r, $result);
    }

    public function obtenerConciliaciones(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT cb.id AS id_comprobante, cb.fechaRegistro, m.id_movimiento,
                   m.tipoMovimiento, m.monto, m.fecha, m.hora, m.concepto
            FROM comprobanteBancario cb
            LEFT JOIN movimientobancario m
                ON cb.movimientoBancarioIdFK = m.id_movimiento
            ORDER BY cb.fechaRegistro DESC
        ";

        $result = $db->select($sql);
        return array_map(fn($r) => (array)$r, $result);
    }
}
