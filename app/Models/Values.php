<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Values extends Model
{
    use HasFactory;

    protected $table = 'tbl_variables';
    protected $primaryKey = 'id_variable';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'id_variable',
        'nemonico',
        'valor',
        'texto',
        'formula',
        'padre'
    ];

}
