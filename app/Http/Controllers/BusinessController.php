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
            'estado' => $request->get('status')
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

    public function getBusiness(Request $request)
    {
        return response()->json([
            'status' => '200',
            'business' => Business::select('id_empresa as id', 'nombre_empresa as name', 'ruc', 'id_empresa_padre as chill')
                ->where('id_usuario', auth()->user()->id_usuario)
                ->where('estado', 'A')
                ->orderBy('id_empresa', 'asc')
                ->get(),
        ], 200);
    }


}
