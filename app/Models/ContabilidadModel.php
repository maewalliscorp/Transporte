<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContabilidadModel extends Model
{
    protected $table = 'ingreso';
    protected $primaryKey = 'id_ingreso';
    public $incrementing = true;
    public $timestamps = false;

    public function obtenerIngresos(): array
    {
        $db = $this->getConnection();

        $sql = "
        SELECT
    i.id_ingreso,
    u.id_unidad,
    i.fecha,
    i.monto

FROM
    ingreso i
JOIN
    unidad u
ON
    i.id_ingreso = u.id_unidad
        ORDER BY i.fecha DESC
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
