<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    protected $table = 'tbl_rubros';
    protected $primaryKey = 'id_rubro';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_rubro',
        'descripcion',
        'nemonico',
        'estado',
        'edita_pp',
        'edita_pa',
        'notas'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];
}
