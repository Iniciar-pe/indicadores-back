<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Business;


class BusinessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get(Request $request)
    {
        return response()->json([
            'status' => '200',
            'business' => Business::select( 'tbl_empresas.id_usuario',
                'tbl_empresas.id_empresa',
                'nombre_empresa', 'ruc', 'tbl_empresas.estado', 'tbl_distribucion_licencias.id_empresa as empresa_defecto' )
                ->leftJoin('tbl_distribucion_licencias' ,'tbl_distribucion_licencias.id_usuario', 'tbl_empresas.id_usuario')
                //->join('tbl_usuarios', 'tbl_usuarios.id_usuario', 'tbl_empresas.id_usuario')
                ->where('tbl_empresas.id_usuario', auth()->user()->id_usuario)
                ->orderBy('tbl_empresas.id_empresa', 'desc')
                ->groupBy("tbl_empresas.id_empresa")
                ->get(),
        ], 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business' => 'required|string|max:255',
            'ruc' => 'required|string|max:11',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $business = $this->incrementing();

        $business = Business::create([
            'id_empresa' => $business ? $business->id_empresa + 1 : '1',
            'id_usuario' => auth()->user()->id_usuario,
            'nombre_empresa' => $request->get('business'),
            'ruc' => $request->get('ruc'),
            'estado' => $request->get('status'),
            'tipo_empresa' => $request->get('type'),
            'id_empresa_padre' => $request->get('index'),
        ]);

        return response()->json([
            'status' => '200',
            'message' => 'Registration registered correctly',
        ], 200);

    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business' => 'required|string|max:255',
            'ruc' => 'required|string|max:11',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $business = Business::find($request->get('id'));
        $business->nombre_empresa = $request->get('business');
        $business->ruc = $request->get('ruc');
        $business->estado = $request->get('status');
        $business->save();

        return response()->json([
            'status' => '200',
            'message' => 'Updated record correctly',
        ], 200);

    }

    public function delete(Request $request)
    {
        $business = Business::where('id_empresa', $request->get('id'))->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Record deleted correctly',
        ], 200);
    }

    private function incrementing()
    {
        return Business::orderBy('id_empresa', 'desc')->first();
    }

    public function getBusiness(Request $request) {

        $default = \App\Models\LicenseDistribution::select('id_usuario as user', 'id_empresa as business', 'id_usuario_asignado as id', 'empresa_defecto as default')
            ->where('id_usuario_asignado', auth()->user()->id_usuario)
            ->where('estado', 'A')
            ->get();

        $lisence = \App\Models\LicenseDistribution::select('id_usuario as user', 'id_empresa as business', 'id_usuario_asignado as id')
            //->where('id_usuario_asignado', auth()->user()->id_usuario)
            ->where('estado', 'A')
            ->first();


        $business = \App\Models\Business::select('id_empresa as id', 'nombre_empresa as name', 'ruc',
            'id_empresa_padre as chill', 'tipo_empresa as type', 'id_usuario as user')
            ->where('id_empresa', $lisence->business)
            ->where('estado', 'A')
            ->whereIn('tbl_empresas.tipo_empresa', explode(",", $request->get('type')))
            ->orderBy('id_empresa', 'asc')
            ->get();

        $array = [];
        $e = 0;
        foreach ($business as $key => $value){
            $bu = new \stdClass();
            $bu->id = $value->id;
            $bu->name = $value->name;
            $bu->ruc = $value->ruc;
            $bu->chill = $value->chill;
            $bu->type = $value->type;
            $bu->user = $value->user;

            $array[$e] = $bu;
            $e++;

            if($value->type == '2') {

                $businessChild = \App\Models\Business::select('id_empresa as id', 'nombre_empresa as name', 'ruc',
                    'id_empresa_padre as chill', 'tipo_empresa as type', 'id_usuario as user')
                    //->where('id_usuario', $lisence->user)
                    ->where('id_empresa_padre',  $value->id)
                    ->where('estado', 'A')
                    ->orderBy('id_empresa', 'asc')
                    ->get();

                foreach ($businessChild as $i => $values){
                    $bus = new \stdClass();
                    $bus->id = $values->id;
                    $bus->name = '  '.$values->name;
                    $bus->ruc = $values->ruc;
                    $bus->chill = $values->chill;
                    $bus->type = $values->type;
                    $bus->user = $values->user;

                    $array[$e] = $bus;
                    $e++;
                }

            }


        }



        return response()->json([
            'status' => '200',
            'business' => $array,
            'default' => $default,
        ], 200);
    }


}
