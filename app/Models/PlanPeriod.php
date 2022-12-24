<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanPeriod extends Model
{
    use HasFactory;
    protected $table = 'tbl_periodos_plan';
    protected $primaryKey = 'id_periodo';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_periodo',
        'descripcion',
        'numero',
        'estado',
    ];

    protected $attributes = [
        'estado' => 'A',
    ];


}
