<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criterion extends Model
{
    use HasFactory;

    protected $table = 'tbl_criterios';
    protected $primaryKey = 'id_criterio';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'id_criterio',
        'id_usuario',
        'id_empresa',
        'id_periodo',
        'id_moneda',
        'mes_inicio',
        'anio_inicio',
        'mes_fin',
        'anio_fin',
        'mes_inicio_pa',
        'anio_inicio_pa',
        'mes_fin_pa',
        'anio_fin_pa',
        'numero_dias',
        'activo',
    ];

}
