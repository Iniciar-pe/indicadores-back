<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Table;

class TableController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get(Request $request)
    {
        return response()->json([
            'status' => '200',
            'table' => Table::orderBy('id_tabla')->get(),
        ], 200);
    }



    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|integer|max:11',
            'description' => 'required|string|max:255',
            'type' => 'required|string|max:1',
            'status' => 'required|string|max:1',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $table_id = $this->incrementing();

        $table = Table::create([
            'id_tabla' => $table_id ? $table_id->id_tabla + 1 : '1',
            'id_valor' => $request->get('value'),
            'descripcion' => $request->get('description'),
            'tipo' => $request->get('type'),
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
            'value' => 'required|integer|max:11',
            'description' => 'required|string|max:255',
            'type' => 'required|string|max:1',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $table = Table::find($request->get('id'));
        $table->id_valor = $request->get('value');
        $table->descripcion = $request->get('description');
        $table->tipo = $request->get('type');
        $table->estado = $request->get('status');
        $table->save();

        return response()->json([
            'status' => '200',
            'message' => 'Updated record correctly',
        ], 200);

    }

    public function delete(Request $request)
    {
        $table = Table::where('id_table', $request->get('id'))->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Record deleted correctly',
        ], 200);
    }

    private function incrementing() 
    {
        return Table::orderBy('id_tabla', 'desc')->first();
    }


}
