<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MantenimientoModel extends Model
{
    protected $table = 'mantenimiento';
    protected $primaryKey = 'id_mantenimiento';
    public $incrementing = true;
    public $timestamps = false;
}
