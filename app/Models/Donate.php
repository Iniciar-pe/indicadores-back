<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donate extends Model
{
    use HasFactory;

    protected $table = 'tbl_donacion';
    protected $primaryKey = 'id_donacion';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'id_plan',
        'id_usuario',
        'id_usuario_invitado',
        'email',
        'comentarios',
        'estado',
        'id_periodo_plan',
        'id_pedido'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];
}
