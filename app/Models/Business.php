<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $table = 'tbl_empresas';
    protected $primaryKey = 'id_empresa';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_usuario',
        'id_empresa',
        'nombre_empresa',
        'ruc',
        'tipo_empresa',
        'id_empresa_padre',
        'estado'
    ];

    protected $attributes = [
        'estado' => 'A',
    ];

    public function businessBranch() {
        return $this->hasMany('App\Models\Business', 'id_empresa', 'id_empresa_padre');
    }

}
