<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'tbl_pedido_detalle';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_pedido',
        'orden',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'id_plan',
    ];

}
