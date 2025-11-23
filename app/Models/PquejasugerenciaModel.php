<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PquejasugerenciaModel extends Model
{
    use HasFactory;

    protected $table = 'quejasugerencia';
    protected $primaryKey = 'id_quejaSugerencia';

    protected $fillable = [
        'id_pasajero',
        'quejaSugerencia',
        'tipoComentario',
        'areaQS'
    ];

    // Desactivacion de TIMESTAMPS
    public $timestamps = false;
}
