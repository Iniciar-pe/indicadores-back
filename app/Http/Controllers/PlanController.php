<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get(Request $request)
    {
        return response()->json([
            'status' => '200',
            'plan' => Plan::orderBy('id_plan', 'desc')->get(),
        ], 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'number' => 'required|integer',
            'price' => 'required',
            'type' => 'required|string|max:1',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $id_plan = $this->incrementingPlan();
        
        $plan = Plan::create([
            'id_plan' => $id_plan ? $id_plan->id_plan + 1 : '1',
            'descripcion' => $request->get('description'),
            'numero' => $request->get('number'),
            'precio' => $request->get('price'),
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
            'description' => 'required|string|max:255',
            'number' => 'required|integer|max:11',
            'price' => 'required|string|max:6',
            'type' => 'required|string|max:1',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $plan = Plan::find($request->get('id'));
        $plan->descripcion = $request->get('description');
        $plan->numero = $request->get('number');
        $plan->precio = $request->get('price');
        $plan->tipo = $request->get('type');
        $plan->estado = $request->get('status');
        $plan->save();

        return response()->json([
            'status' => '200',
            'message' => 'Updated record correctly',
        ], 200);

    }

    public function delete(Request $request)
    {
        $plan = Plan::where('id_plan', $request->get('id'))->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Record deleted correctly',
        ], 200);
    }

    private function incrementingPlan() 
    {
        return Plan::orderBy('id_plan', 'desc')->first();
    }

}
