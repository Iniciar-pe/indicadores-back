<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    protected $table = 'tbl_tablas';
    protected $primaryKey = 'id_tabla';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_tabla',
        'id_valor',
        'descripcion',
        'tipo',
        'estado'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];
}
