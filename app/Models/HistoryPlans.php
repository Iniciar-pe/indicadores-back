<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPlans extends Model
{
    use HasFactory;

    protected $table = 'tbl_historial_planes';
    protected $primaryKey = 'id_historial';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_periodo_plan',
        'id_usuario',
        'id_historial',
        'fecha_inicio',
        'fecha_fin',
        'numero',
        'id_plan',
        'estado',
        'id_pedido',
        'orden'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];
}
