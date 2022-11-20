<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Indicator;
use App\Models\Template;

class IndicatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get(Request $request)
    {
        return response()->json([
            'status' => '200',
            'indicator' => Indicator::orderBy('id_indicador', 'desc')->get(),
            'template' => Template::orderBy('id_plantilla', 'desc')->get()
        ], 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:1',
            'formula' => 'required|string|max:255',
            'public' => 'required|string|max:1',
            //'id_payroll' => 'required|integer|max:11',
            'order' => 'required|integer|max:50',
            'expressed' => 'required|string|max:1',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $indicador = $this->incrementing();
        //dd($indicador);

        $indicador = Indicator::create([
            'id_indicador' => $indicador ? $indicador->id_indicador + 1 : '1',
            'descripcion' => $request->get('description'),
            'nombre' => $request->get('name'),
            'formula' => $request->get('formula'),
            'publico' => $request->get('public'),
            'id_plantilla' => $this->idTemplate()->id_plantilla,
            'orden' => $request->get('order'),
            'expresado' => $request->get('expressed'),
            'estado' => $request->get('status'),
            'tipo' => $request->get('type'),
        ]);

        return response()->json([
            'status' => '200',
            'message' => 'Registration registered correctly',
        ], 200);

    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:1',
            'formula' => 'required|string|max:255',
            'public' => 'required|string|max:1',
            //'id_payroll' => 'required|integer|max:11',
            'order' => 'required|integer|max:50',
            'expressed' => 'required|string|max:1',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $indicador = Indicator::find($request->get('id'));
        $indicador->descripcion = $request->get('description');
        $indicador->nombre = $request->get('name');
        $indicador->formula = $request->get('formula');
        $indicador->publico = $request->get('public');

        $indicador->id_plantilla = $this->idTemplate()->id_plantilla;
        $indicador->orden = $request->get('order');
        $indicador->expresado = $request->get('expressed');
        $indicador->estado = $request->get('status');
        $indicador->tipo = $request->get('type');
        $indicador->save();

        return response()->json([
            'status' => '200',
            'message' => 'Updated record correctly',
        ], 200);

    }

    public function delete(Request $request)
    {

        $entry = Indicator::where('id_indicador', $request->get('id'))->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Record deleted correctly',
        ], 200);
    }

    private function upload(Request $request)
    {
        if(!$request->hasFile('icono') && !$request->file('icono')->isValid()) {
            return esponse()->json('{"error": "ddd"}');
        }

        try {

            $file = $request->file('icono')->getClientOriginalName();
            Storage::disk('local')->put($file, file_get_contents($request->file('icono')));
            //$name_file = Storage::disk('local')->url($file);
            return $file;
        } catch (\Throwable $th) {
            return response()->json($th);
        }

    }

    private function incrementing()
    {
        return Indicator::orderBy('id_indicador', 'desc')->first();
    }

    private function idTemplate() {
        return Template::where('pdefault', 'S')->first();
    }

}
