<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'tbl_monedas';
    protected $primaryKey = 'id_moneda';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_moneda',
        'identificador',
        'simbolo',
        'descripcion',
        'pais',
        'estado'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];
}
