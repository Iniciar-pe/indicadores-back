<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\LicenseDistribution;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Business;
use App\Models\HistoryPlans;
use DB;

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
            'license' => LicenseDistribution::select('foto as avatar', 'tbl_usuarios.apellidos as lastName', 'nombre_empresa as description',
                'tbl_usuarios.estado as status',
                'tbl_usuarios.nombres as name', 'tbl_distribucion_licencias.id_empresa as business', 'tbl_usuarios.email',
                'empresa_defecto as default', 'tbl_distribucion_licencias.id_usuario_asignado as id', 'tbl_usuarios.tipo as type', 'id_historial as group')
                ->Join('tbl_empresas','tbl_empresas.id_empresa', 'tbl_distribucion_licencias.id_empresa')
                ->Join('tbl_usuarios','tbl_usuarios.id_usuario', 'tbl_distribucion_licencias.id_usuario_asignado')
                ->where([
                    'tbl_distribucion_licencias.id_usuario' => auth()->user()->id_usuario,
                ])
                ->where('id_plan', '!=', '1')
                ->whereIn('tbl_empresas.tipo_empresa', explode(",", $request->get('type')))
                ->orderBy('tbl_distribucion_licencias.id_empresa', 'desc')->get(),
            'business' => Business::where([
                'id_usuario' => auth()->user()->id_usuario,
                'estado' => 'A'
            ])->orderBy('id_empresa', 'desc')->get(),
        ], 200);
    }

    public function getGroup(Request $request) {

        $type = $request->get('type') == '1' ? '4' : '5';

        $group = HistoryPlans::select('id_historial as id', 'fecha_inicio as start', 'fecha_fin as end', 'numero as number',
        'id_plan as plan', 'id_periodo_plan as period',
        DB::raw('(select count(*) from tbl_distribucion_licencias where tbl_distribucion_licencias.id_historial = id and estado = "A") as cant'))
            ->where([
                'id_usuario' => auth()->user()->id_usuario,
            ])
            ->where('tbl_historial_planes.id_plan', $type)
            //->join('tbl_planes', 'tbl_planes.id_plan', '=', 'tbl_historial_planes.id_plan')
            ->get();

        return response()->json([
            'status' => '200',
            'group' =>  $group,
        ], 200);

    }

    public function getListBusiness(Request $request) {
        return response()->json([
            'status' => '200',
            'listBusiness' => LicenseDistribution::select('nombre_empresa as description', 'tbl_distribucion_licencias.estado as status',
                'tbl_distribucion_licencias.id_empresa as business', 'id_historial as group')
                ->Join('tbl_empresas','tbl_empresas.id_empresa', 'tbl_distribucion_licencias.id_empresa')
                ->where([
                    'tbl_distribucion_licencias.id_usuario_asignado' => $request->get('user'),
                    'tbl_distribucion_licencias.estado' => 'A'
                ])
                ->orderBy('tbl_distribucion_licencias.id_empresa', 'desc')->get()
        ], 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            //'password' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $existUser = $this->existUser($request);

        if ($existUser) {

            return response()->json([
                'status' => '400',
                'message' => 'the user already exists',
            ], 400);
        } else {

            $usuario = $this->incrementingUser();

            $user = User::create([
                'id_usuario' => $usuario->id_usuario + 1,
                'apellidos' => $request->get('lastName'),
                'email' => $request->get('email'),
                'nombres' => $request->get('name'),
                'password' => Hash::make($request->get('password')),
                'tipo' => 'U',
                'ubi_codigo' => '1',
                'estado' => $request->get('status'),
                'foto' => '/app/avatar.png',
            ]);
        }

        $json = json_decode($request->detail);

        LicenseDistribution::where([
            'id_usuario' => auth()->user()->id_usuario,
            'id_usuario_asignado' => $user->id_usuario
        ])
        ->update([
            'estado' => 'I',
        ]);

        $history = HistoryPlans::where('id_historial', $request->get('group'))->first();

        foreach ($json as $key => $value) {

            if ($value->type != '1') {
                // Consultar si existe un licencia para
                $licenseExist = LicenseDistribution::where([
                    'id_usuario' => auth()->user()->id_usuario,
                    'id_empresa' => $value->type == '2' ? $value->id : $value->chill,
                    'id_usuario_asignado' => auth()->user()->id_usuario,
                ])->exists();

                if(!$licenseExist) {
                    LicenseDistribution::create([
                        'id_usuario' => auth()->user()->id_usuario,
                        'id_empresa' => $value->type == '2' ? $value->id : $value->chill,
                        'id_usuario_asignado' => auth()->user()->id_usuario,
                        'estado' => 'A',
                        'id_historial' => $request->get('group'),
                        'id_plan' => $request->get('plan'),
                        'fecha_inicio' => $history->fecha_inicio,
                        'fecha_fin' => $history->fecha_fin,
                        'empresa_defecto' => 'S'
                    ]);
                }
            }

            // $response = $value->json();
            $exist =  LicenseDistribution::where([
                'id_usuario' => auth()->user()->id_usuario,
                'id_empresa' => $value->id,
                'id_usuario_asignado' => $user->id_usuario
            ])->exists();

            if ($exist) {
                LicenseDistribution::where([
                    'id_usuario' => auth()->user()->id_usuario,
                    'id_empresa' => $value->id,
                    'id_usuario_asignado' => $user->id_usuario
                ])
                ->update([
                    'estado' => 'A',
                    'id_historial' => $request->get('group'),
                    'id_plan' => $request->get('plan'),
                    'fecha_inicio' => $history->fecha_inicio,
                    'fecha_fin' => $history->fecha_fin,
                    //'empresa_defecto' => $request->get('default')
                ]);
            } else {
                LicenseDistribution::create([
                    'id_usuario' => auth()->user()->id_usuario,
                    'id_empresa' => $value->id,
                    'id_usuario_asignado' => $user->id_usuario,
                    'estado' => 'A',
                    'id_historial' => $request->get('group'),
                    'id_plan' => $request->get('plan'),
                    'fecha_inicio' => $history->fecha_inicio,
                    'fecha_fin' => $history->fecha_fin,
                    'empresa_defecto' => 'S'
                ]);
            }

        }

        return response()->json([
            'status' => '200',
            'message' => 'Registration registered correctly',
        ], 200);

    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            //'password' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }


        /* Se actualiza datos de usuario */
        $user = User::find($request->get('user'));
        $user->nombres = $request->get('name');
        $user->apellidos = $request->get('lastName');
        $user->email = $request->get('email');
        $user->estado = $request->get('status');
        // Valida si password viene con data
        if($request->get('password') && $request->get('password') != ''){
            $user->password = Hash::make($request->get('password'));
        }

        $user->save();

        // Se actualzia Licencias

        $json = json_decode($request->detail);

        LicenseDistribution::where([
            'id_usuario' => auth()->user()->id_usuario,
            'id_usuario_asignado' => $request->get('user')
        ])
        ->update([
            'estado' => 'I',
        ]);

        $history = HistoryPlans::where('id_historial', $request->get('group'))->first();

        foreach ($json as $key => $value) {
            // $response = $value->json();
            $exist =  LicenseDistribution::where([
                'id_usuario' => auth()->user()->id_usuario,
                'id_empresa' => $value->id,
                'id_usuario_asignado' => $request->get('user')
            ])->first();

            if ($exist) {
                LicenseDistribution::where([
                    'id_usuario' => auth()->user()->id_usuario,
                    'id_empresa' => $value->id,
                    'id_usuario_asignado' => $request->get('user')
                ])
                ->update([
                    'estado' => 'A',
                    'id_historial' => $request->get('group'),
                    'id_plan' => $request->get('plan'),
                    'fecha_inicio' => $history->fecha_inicio,
                    'fecha_fin' => $history->fecha_fin,
                    //'empresa_defecto' => $request->get('default')
                ]);
            } else {
                LicenseDistribution::create([
                    'id_usuario' => auth()->user()->id_usuario,
                    'id_empresa' => $value->id,
                    'id_usuario_asignado' => $request->get('user'),
                    'estado' => 'A',
                    'id_historial' => $request->get('group'),
                    'fecha_inicio' => $history->fecha_inicio,
                    'fecha_fin' => $history->fecha_fin,
                    'fecha_fin' => $request->get('dateEnd'),
                    'id_plan' => $request->get('plan')
                ]);
            }





        }

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
