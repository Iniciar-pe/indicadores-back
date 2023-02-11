<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donate;
use Illuminate\Support\Facades\Crypt;

class DonateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getDonates() {

        $donate = Donate::select('tbl_donacion.estado as status', 'id_donacion as id', 'nombres as name',
            'fecha_registro as date', 'comentarios as comment')
            ->where([
                'tbl_donacion.id_usuario' => auth()->user()->id_usuario
            ])
            ->leftJoin('tbl_usuarios', 'tbl_usuarios.id_usuario', '=', 'tbl_donacion.id_usuario_invitado')
            ->get();

        $array = [];
        foreach ($donate as $key => $value){
            $bu = new \stdClass();
            $bu->id = $value->id;
            $bu->status = $value->status;
            $bu->name = $value->name;
            $bu->date = $value->date;
            $bu->comment = $value->comment;
            $bu->token = Crypt::encrypt($value->id);
            $array[$key] = $bu;

        }

        return response()->json([
            'status' => '200',
            'donate' => $array,
        ], 200);

    }


}
