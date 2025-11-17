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
            SELECT t.id_tarifa, t.id_ruta, r.nombre AS ruta, t.tarifaBaseRuta, t.tipoPasajero,
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

    // Métodos CRUD para movimientos bancarios (ingresos y egresos)
    public function crearMovimiento(array $datos)
    {
        return $this->getConnection()
            ->table('movimientobancario')
            ->insert($datos);
    }

    public function actualizarMovimiento($id, array $datos)
    {
        return $this->getConnection()
            ->table('movimientobancario')
            ->where('id_movimiento', $id)
            ->update($datos);
    }

    public function eliminarMovimiento($id)
    {
        return $this->getConnection()
            ->table('movimientobancario')
            ->where('id_movimiento', $id)
            ->delete();
    }

    public function obtenerMovimientoPorId($id)
    {
        $db = $this->getConnection();
        $result = $db->table('movimientobancario')
            ->where('id_movimiento', $id)
            ->first();
        return $result ? (array)$result : null;
    }

    // Métodos CRUD para tarifas
    public function crearTarifa(array $datos)
    {
        return $this->getConnection()
            ->table('tarifa')
            ->insert($datos);
    }

    public function actualizarTarifa($id, array $datos)
    {
        return $this->getConnection()
            ->table('tarifa')
            ->where('id_tarifa', $id)
            ->update($datos);
    }

    public function eliminarTarifa($id)
    {
        return $this->getConnection()
            ->table('tarifa')
            ->where('id_tarifa', $id)
            ->delete();
    }

    public function obtenerTarifaPorId($id)
    {
        $db = $this->getConnection();
        $result = $db->table('tarifa')
            ->where('id_tarifa', $id)
            ->first();
        return $result ? (array)$result : null;
    }

    // Método para crear conciliación
    public function crearConciliacion(array $datos)
    {
        return $this->getConnection()
            ->table('comprobanteBancario')
            ->insert($datos);
    }

    // Método para obtener rutas (para el select de tarifas)
    public function obtenerRutas()
    {
        $db = $this->getConnection();
        $sql = "SELECT id_ruta, nombre, origen, destino FROM ruta ORDER BY nombre";
        $result = $db->select($sql);
        return array_map(fn($row) => (array)$row, $result);
    }
}
