<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    use HasFactory;
    protected $table = 'tbl_valores';
    // protected $primaryKey = 'id_tabla';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_criterio',
        'id_rubro',
        'valor_pp',
        'valor_pa',
        'estado',
        'id_empresa',
        'id_usuario'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];
}
