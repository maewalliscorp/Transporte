<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContabilidadModel extends Model
{
    protected $table = 'asignacion';
    protected $primaryKey = 'id_asignacion';
    public $incrementing = true;
    public $timestamps = false;

    public function obtenerIngresos(): array
    {
        $db = $this->getConnection();

        $sql = "
            SELECT
            u.id_unidad, u.placa, u.modelo, u.capacidad,
            o.id_operator, o.licencia,

            i.id_ingreso, i.fecha, i.monto
        FROM ingreso i
        LEFT JOIN asignacion a ON i.id_ingreso = a.id_ingreso
        LEFT JOIN unidad u ON a.id_unidad = u.id_unidad
        LEFT JOIN operador o ON a.id_operador = o.id_operator
        ORDER BY i.fecha ASC
        ";

        $result = $db->select($sql);
        return array_map(fn($row) => (array) $row, $result);
    }

    public function obtenerEgresos(): array
    {
        $db = $this->getConnection();

        $sql = "SELECT id_egreso, concepto, comprobante, cantidad, fecha
                FROM egreso
                ORDER BY fecha ASC, id_egreso ASC";

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerTotalEgresos(): float
    {
        $db = $this->getConnection();

        $sql = "SELECT SUM(cantidad) as total FROM egreso";
        $result = $db->select($sql);

        return $result[0]->total ?? 0.00;
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

    public function obtenerTarifas(): array
    {
        $db = $this->getConnection();

        $sql = "SELECT id_tarifa, id_ruta, tarifaBaseRuta, tipoPasajero, descuentoPasajero, tarifaFinal, notas
            FROM tarifa
            ORDER BY id_tarifa ASC";

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerBancarios(): array
    {
        $db = $this->getConnection();

        $sql = "SELECT id_movimiento, tipoMovimiento, monto, fecha, hora
            FROM movimientobancario
            ORDER BY fecha DESC, id_movimiento ASC";

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }

    public function obtenerTotalBancarios(): array
    {
        $db = $this->getConnection();

        $sql = "SELECT
                tipoMovimiento,
                SUM(monto) as total
            FROM movimientobancario
            GROUP BY tipoMovimiento";

        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }
}
