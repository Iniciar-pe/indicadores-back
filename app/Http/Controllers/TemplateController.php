<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Template;

class TemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get(Request $request)
    {
        return response()->json([
            'status' => '200',
            'template' => Template::orderBy('id_plantilla', 'desc')->get(),
        ], 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            //'name' => 'required|max:255',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $file = $this->upload($request);

        $template = $this->incrementingPlan();
        
        $template = Template::create([
            'id_plantilla' => $template ? $template->id_plantilla + 1 : '1',
            'descripcion' => $request->get('description'),
            'nombre_documento' => $file,
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
            'description' => 'required|string|max:255',
            //'name' => 'required|max:255',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $template = Template::find($request->get('id'));

        if($request->hasFile('name') && $request->file('name')->isValid()) {
            $file = $this->upload($request);
            $template->nombre_documento = $file;
        }
        
        $template->descripcion = $request->get('description');
        $template->estado = $request->get('status');
        $template->save();

        return response()->json([
            'status' => '200',
            'message' => 'Updated record correctly',
        ], 200);

    }

    public function delete(Request $request)
    {
        $template = Template::where('id_plantilla', $request->get('id'))->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Record deleted correctly',
        ], 200);
    }

    private function upload(Request $request) 
    {
        if(!$request->hasFile('name') && !$request->file('name')->isValid()) {
            return '';
        }

        try {
            $file = $request->file('name')->getClientOriginalName();
            Storage::disk('local')->put($file, file_get_contents($request->file('name')));
            return $file;
        } catch (\Throwable $th) {
            return response()->json($th);
        }

    }

    private function incrementingPlan() 
    {
        return Template::orderBy('id_plantilla', 'desc')->first();
    }
}
