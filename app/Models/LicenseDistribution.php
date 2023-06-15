<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseDistribution extends Model
{
    use HasFactory;

    protected $table = 'tbl_distribucion_licencias';
    //protected $primaryKey = 'id_usuario_asignado';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_usuario',
        'id_empresa',
        'id_usuario_asignado',
        'empresa_defecto',
        'estado',
        'id_plan',
        'id_historial',
        'fecha_inicio',
        'fecha_fin'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];


}
