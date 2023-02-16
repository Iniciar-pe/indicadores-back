<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donate;
use App\Mail\InvitationMail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class DonateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getDonates() {

        $donate = Donate::select('tbl_donacion.estado as status', 'id_donacion as id', 'nombres as name',
            'fecha_registro as date', 'comentarios as comment', 'tbl_usuarios.email')
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
            $bu->email = $value->email;
            $bu->token = Crypt::encrypt($value->id);
            $array[$key] = $bu;

        }

        return response()->json([
            'status' => '200',
            'donate' => $array,
        ], 200);

    }

    public function mailSend(Request $request) {
        $mail = Mail::to($request->mail)->send(new InvitationMail($request));
        return response()->json([
            'status' => '200',
            'mail' => $mail
        ], 200);
    }


}
