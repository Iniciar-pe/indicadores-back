<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodCalculation extends Model
{
    use HasFactory;

    protected $table = 'tbl_periodos_calculo';
    protected $primaryKey = 'id_periodo';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_periodo',
        'nombre_periodo',
        'indicador',
        'cantidad_meses',
        'estado'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];
}
