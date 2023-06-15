<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanRange extends Model
{
    use HasFactory;
    protected $table = 'tbl_planes_rango';
    protected $primaryKey = 'id_rango';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_rango',
        'id_plan',
        'id_periodo',
        'rango_inicio',
        'rango_fin',
        'precio',
    ];


}
