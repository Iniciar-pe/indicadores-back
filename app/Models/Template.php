<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $table = 'tbl_plantillas';
    protected $primaryKey = 'id_plantilla';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_plantilla',
        'descripcion',
        'nombre_documento',
        'estado',
        'pdefault'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];
}
