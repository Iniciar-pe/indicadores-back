<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $table = 'tbl_resultados';
    protected $primaryKey = 'id_resultado';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_resultado',
        'id_criterio',
        'id_indicador',
        'id_usuario',
        'id_empresa',
        'resultado',
        'valores',
        'formula',
        'formula_descifrada',
        'validacion_formula'
    ];


}
