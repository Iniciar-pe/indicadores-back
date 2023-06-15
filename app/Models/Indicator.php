<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use HasFactory;

    protected $table = 'tbl_indicadores';
    protected $primaryKey = 'id_indicador';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_indicador',
        'nombre',
        'descripcion',
        'tipo',
        'formula',
        'publico',
        'id_plantilla',
        'orden',
        'expresado',
        'estado',
        'icono',
        'detalle_resultado',
        'formula_mostrar',
        'nemonico',
        'lista_variables'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];

    /*public function template() {
        return $this->hasOne('App\Models\Template', 'id_plantilla', 'id_plantilla');
    }*/

}
