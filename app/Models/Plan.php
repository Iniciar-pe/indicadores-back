<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $table = 'tbl_planes';
    protected $primaryKey = 'id_plan';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_plan',
        'descripcion',
        'numero',
        'precio',
        'tipo',
        'estado'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];


}
