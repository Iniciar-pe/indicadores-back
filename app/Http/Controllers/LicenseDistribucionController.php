<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\LicenseDistribution;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Business;

class LicenseDistribucionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get(Request $request)
    {
        return response()->json([
            'status' => '200',
            'license' => LicenseDistribution::select('foto as avatar', 'tbl_usuarios.apellidos as user', 'nombre_empresa as description', 'tbl_distribucion_licencias.estado as status',
                'tbl_usuarios.nombres as name', 'tbl_distribucion_licencias.id_empresa as business', 'tbl_usuarios.email',
                'empresa_defecto as default', 'tbl_distribucion_licencias.id_usuario_asignado as id', 'tbl_usuarios.tipo as type')
                ->Join('tbl_empresas','tbl_empresas.id_empresa', 'tbl_distribucion_licencias.id_empresa')
                ->Join('tbl_usuarios','tbl_usuarios.id_usuario', 'tbl_distribucion_licencias.id_usuario_asignado')
                ->where('tbl_distribucion_licencias.id_usuario', auth()->user()->id_usuario)
                ->orderBy('tbl_distribucion_licencias.id_empresa', 'desc')->get(),
            'business' => Business::where([
                'id_usuario' => auth()->user()->id_usuario,
                'estado' => 'A'
            ])->orderBy('id_empresa', 'desc')->get(),
        ], 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'business' => 'required|integer|max:255',
            'email' => 'required|string|email|max:255',
            //'password' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $existUser = $this->existUser($request);

        if ($existUser) {

            $user = User::find($existUser->id_usuario);
            $user->usuario = $request->get('user');
            $user->usuario = $request->get('user');
            $user->nombres = $request->get('name');
            $user->email = $request->get('email');

            if($request->get('password') && $request->get('password') != ''){
                $user->password = Hash::make($request->get('password'));
            }
            $user->save();

        } else {

            /*if ($request->get('password') == '') {
                return response()->json([
                    'code' => '401',
                    'status' => '400',
                    'message' => 'Se requiere una contraseña para el usuario',
                    'errors' => '{"message":["Se requiere una contraseña para el usuario."]}'
                ], 400);
            }*/

            $usuario = $this->incrementingUser();

            $user = User::create([
                'id_usuario' => $usuario ? $usuario->id_usuario + 1 : '1',
                'apellidos' => $request->get('user'),
                'email' => $request->get('email'),
                'nombres' => $request->get('name'),
                'password' => Hash::make($request->get('password')),
                'tipo' => 'U',
                'ubi_codigo' => '1',
                'usuario' => $this->formatUser($request),
                'foto' => '/app/avatar.png',
            ]);
        }

        /*if ($request->get('default') == 'S') {
            LicenseDistribution::where('id_usuario', auth()->user()->id_usuario)
            //->where('id_empresa', $request->get('business'))
            ->where('id_usuario_asignado', $user->id_usuario)
            ->update([
                'empresa_defecto' => 'N'
            ]);
        }*/


        $license = LicenseDistribution::create([
            'id_usuario' => auth()->user()->id_usuario,
            'id_empresa' => $request->get('business'),
            'id_usuario_asignado' => $user->id_usuario,
            //'empresa_defecto' => $request->get('default'),
            'estado' => $request->get('status'),
            'id_plan' => '1'
        ]);

        return response()->json([
            'status' => '200',
            'message' => 'Registration registered correctly',
        ], 200);

    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'business' => 'required|integer|max:255',
            'email' => 'required|string|email|max:255',
            //'password' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $emailExist = User::where('email', $request->get('email'))
            ->where('id_usuario', '!=', $request->get('id'))
            ->first();

        if($emailExist){
            return response()->json("{ code: '0001', message: 'The new email already exists' }", 400);
        }

        /* Se actualiza datos de usuario */
        $user = User::find($request->get('id'));
        $user->usuario = $request->get('user');
        $user->nombres = $request->get('name');
        $user->email = $request->get('email');
        // Valida si password viene con data
        if($request->get('password') && $request->get('password') != ''){
            $user->password = Hash::make($request->get('password'));
        }

        $user->save();

        // Se actualzia Licencias

        /*if ($request->get('default') == 'S') {
            LicenseDistribution::where('id_usuario', auth()->user()->id_usuario)
            //->where('id_empresa', $request->get('business'))
            ->where('id_usuario_asignado', $request->get('id'))
            ->update([
                'empresa_defecto' => 'N'
            ]);
        }*/

        $license = LicenseDistribution::where('id_usuario', auth()->user()->id_usuario)
            ->where('id_empresa', $request->get('business'))
            ->where('id_usuario_asignado', $request->get('id'))
            ->update([
                'estado'=> $request->get('status'),
                //'empresa_defecto' => $request->get('default')
            ]);

        return response()->json([
            'status' => '200',
            'message' => 'Updated record correctly',
        ], 200);

    }

    public function delete(Request $request)
    {
        $template = LicenseDistribution::where('id_usuario_asignado', $request->get('id'))->delete();
        $usuario = User::where('id_usuario', $request->get('id'))->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Record deleted correctly',
        ], 200);
    }

    private function existUser(Request $request){
        return User::where('email', $request->get('email'))
            //->where('id_usuario', '!=', auth()->user()->id_usuario)
            ->first();
    }

    private function incrementingUser()
    {
        return User::orderBy('id_usuario', 'desc')->first();
    }

    public function formatUser(Request $request)
    {
        $Name = $request->get('firstName');
        $Ape = $request->get('lastName');

        $fNom = explode(" ", $Name);
        $fApe = explode(" ", $Ape);

        return (count($fNom) > 0 ? $fNom[0] : $Name) . (count($fApe) > 0 ? $fApe[0] : $Ape);

    }

}
