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

        $planes = Plan::select('descripcion as name', 'id_plan as id', 'tipo_licencia as type')
            ->where('tipo_licencia', '!=', null)
            ->where('estado', 'A')
            ->get();

        foreach ($planes as $key => $value) {
            $pla = new \stdClass();
            $pla->id = $value->id;
            $pla->name = $value->name;
            $pla->type = $value->type;

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
                $range = PlanRange::select('id_rango as id', 'id_periodo as idPeriod',
                    'rango_inicio as start', 'rango_fin as end', 'precio as price')
                    ->where('id_plan',  $pla->id)
                    ->get();


                foreach ($range as $i => $item) {
                    $ran = new \stdClass();
                    $ran->id = $value->id;
                    $ran->idPeriod = $value->idPeriod;
                    $ran->start = $value->start;
                    $ran->end = $value->end;
                    $ran->price = $value->price;
                    $arrayRange[$e] = $ran;
                }

                $per->rage = $arrayRange;
                $arrayPeriod[$e] = $per;

            }


            $pla->period = $arrayPeriod;
            $arrayPlanes[$key] = $pla;
        }

        return response()->json([
            'status' => '200',
            'planes' => $arrayPlanes,
        ], 200);

    }

}
