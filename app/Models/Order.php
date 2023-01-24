<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'tbl_pedidos';
    protected $primaryKey = 'id_pedido';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'id_pedido',
        'id_usuario',
        'nombre',
        'telefono',
        'direccion',
        'pais',
        'ciudad',
        'codigo_postal',
        'fecha',
        'hora',
        'importe',
        'metodo_de_pago',
        'repuesta_pago',
        'numero_pedido'
    ];

}
