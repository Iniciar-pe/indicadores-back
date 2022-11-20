<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    use HasFactory;
    protected $table = 'tbl_plan_usuario';
    // protected $primaryKey = 'id_tabla';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_usuario',
        'id_plan',
        'estado'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];
}
