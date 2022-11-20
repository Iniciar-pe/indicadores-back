<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $table = 'tbl_empresas';
    protected $primaryKey = 'id_empresa';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_usuario',
        'id_empresa',
        'nombre_empresa',
        'ruc',
        'estado'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];
}
