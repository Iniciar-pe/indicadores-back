<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\PlanRange;
use App\Models\PlanPeriod;

class EcommerceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getPlanes() {

        $arrayPlanes = [];

        $planes = Plan::select('nombre as name', 'descripcion as description', 'imagen as image', 'id_plan as id', 'tipo_licencia as type')
            ->where('tipo', '=', 'L')
            ->where('estado', 'A')
            ->get();

        foreach ($planes as $key => $value) {
            $pla = new \stdClass();
            $pla->id = $value->id;
            $pla->name = $value->name;
            $pla->type = $value->type;
            $pla->description = $value->description;
            $pla->image = $value->image;

            $arrayPeriod = [];
            $periodSql = PlanPeriod::select('id_periodo as id', 'descripcion as description',
            'numero as number')
            ->where('estado', 'A')
            ->get();


            foreach ($periodSql as $e => $periodIten) {
                $per = new \stdClass();
                $per->id = $periodIten->id;
                $per->description = $periodIten->description;
                $per->number = $periodIten->number;

                $arrayRange = [];
                $range = PlanRange::select('id_rango as id', 'rango_inicio as start', 'rango_fin as end', 'precio as price')
                    ->where([
                        'id_plan' => $pla->id,
                        'id_periodo' => $per->id,
                    ])
                    ->get();


                foreach ($range as $i => $item) {
                    $ran = new \stdClass();
                    $ran->id = $item->id;
                    $ran->start = $item->start;
                    $ran->end = $item->end;
                    $ran->price = $item->price;
                    $arrayRange[$i] = $ran;
                }

                $per->range = $arrayRange;
                $arrayPeriod[$e] = $per;

            }


            $pla->period = $arrayPeriod;
            $arrayPlanes[$key] = $pla;
        }


        $periodPlan = PlanPeriod::select('id_periodo as id', 'descripcion as description',
            'numero as number')
            ->where('estado', 'A')
            ->get();

        return response()->json([
            'status' => '200',
            'planes' => $arrayPlanes,
            'period' => $periodPlan
        ], 200);

    }

}
