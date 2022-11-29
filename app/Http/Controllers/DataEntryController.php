<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\PeriodCalculation;
use App\Models\Currency;
use App\Models\Criterion;
use App\Models\Entry;
use App\Models\Value;
use Carbon\Carbon;

class DataEntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getEntryData(Request $request)
    {

        $periodo = PeriodCalculation::select('id_periodo as id', 'nombre_periodo as name', 'estado as status', 'cantidad_meses as count')
            ->where('estado', 'A')
            ->orderBy('id_periodo', 'asc')->get();

        $currency = Currency::select('id_moneda as id', 'simbolo as symbol', 'descripcion as description', 'estado as status')
            ->where('estado', 'A')
            ->orderBy('id_moneda', 'asc')->get();

        if ($request->get('type') == '3') {

            $criterion = Criterion:: select('id_criterio as id', 'id_empresa as business', 'id_periodo as period', 'id_moneda as currency', 'mes_inicio as startMonth',
                'anio_inicio as startYear', 'mes_fin as endMonth', 'anio_fin as endYear', 'mes_inicio_pa as startMonthPeriod',
                'anio_inicio_pa as startYearPeriod', 'mes_fin_pa as endMonthPeriod', 'anio_fin_pa as endYearPeriod')
                ->where([
                    'id_usuario' => $request->get('user'),
                    'activo' => 'A',
                    'id_empresa' => $request->get('chill')
                ])->orderBy('id_criterio', 'desc')
                ->first();


        } else {

            $criterion = Criterion:: select('id_criterio as id', 'id_empresa as business', 'id_periodo as period', 'id_moneda as currency', 'mes_inicio as startMonth',
                'anio_inicio as startYear', 'mes_fin as endMonth', 'anio_fin as endYear', 'mes_inicio_pa as startMonthPeriod',
                'anio_inicio_pa as startYearPeriod', 'mes_fin_pa as endMonthPeriod', 'anio_fin_pa as endYearPeriod')
                ->where([
                    'id_usuario' => auth()->user()->id_usuario,
                    'activo' => 'A',
                    'id_empresa' => $request->get('id')
                ])->orderBy('id_criterio', 'desc')
                ->first();

        }



        return response()->json([
            'status' => '200',
            'period' => $periodo,
            'currency' => $currency,
            'criterion' => $criterion,
        ], 200);
    }

    public function addEntryData(Request $request) {

        $criterion = "";

        $exist = Criterion::where([
            'id_usuario' => auth()->user()->id_usuario,
            'id_empresa' => $request->get('business'),
            'id_periodo' => $request->get('period'),
            //'id_moneda' => $request->get('currency'),
            'mes_inicio' => $request->get('month'),
            'anio_inicio' => $request->get('year'),
        ])->first();



        if(!$exist) {

            $period = PeriodCalculation::where('id_periodo', $request->get('period'))->first();

            $date = Carbon::parse($request->get('year').'-'.$request->get('month').'-01');
            $addMonth = $date->addMonths($period->cantidad_meses);

            $dateStart = Carbon::parse($request->get('year').'-'.$request->get('month').'-01');
            $startPa = $dateStart->subMonths($period->cantidad_meses +1);

            $endPa = Carbon::parse($request->get('year').'-'.$request->get('month').'-01');
            $endMonth = $endPa->subMonth();


            $getCriterion = Criterion::create([
                'id_usuario' => auth()->user()->id_usuario,
                'id_empresa' => $request->get('business'),
                'id_periodo' => $request->get('period'),
                'id_moneda' => $request->get('currency'),
                'mes_inicio' => $request->get('month'),
                'anio_inicio' => $request->get('year'),
                'mes_fin' => $addMonth->format('m'),
                'anio_fin' => $addMonth->format('Y'),
                'mes_inicio_pa' => $startPa->format('m'),
                'anio_inicio_pa' => $startPa->format('Y'),
                'mes_fin_pa' => $endMonth->format('m'),
                'anio_fin_pa' => $endMonth->format('Y'),
                'numero_dias' => $request->get('countDays'),
                'activo' => 'A'
            ]);

            $criterion = $getCriterion->id_criterio;

            // Se trae lista de rubros

            $entry = Entry::where('estado', 'A')->orderBy('id_rubro', 'desc')->get();

            foreach ($entry as $value) {

                Value::create([
                    'id_criterio' => $getCriterion->id_criterio,
                    'id_rubro' => $value->id_rubro,
                    'valor_pp' => '0',
                    'valor_pa' => '0',
                    'estado' => 'A'
                ]);
            }

            $value = Value::where('id_criterio')->first();

        } else {
            $criterion = $exist->id_criterio;
        }



        return response()->json([
            'status' => '200',
            'criterion' => $criterion,
        ], 200);

    }

    public function getVelues(Request $request) {

        $response = '';

        $values = Entry::select('tbl_rubros.id_rubro as id','descripcion as description', 'nemonico as name',
             'valor_pp as currentPeriod', 'valor_pa as previousPeriod', 'edita_pp as previousEdit', 'edita_pa as currentEdit', 'notas as note')
            ->where([
                'tbl_rubros.estado' => 'A',
                'tbl_valores.id_criterio' =>  $request->get('criterion'),
            ])
            ->leftJoin('tbl_valores', 'tbl_valores.id_rubro', '=', 'tbl_rubros.id_rubro')
            ->get();

        /*if($values->isEmpty()) {
            $values = Entry::select('tbl_rubros.id_rubro as id','descripcion as description', 'nemonico as name',
                'edita_pp as previousEdit', 'edita_pa as currentEdit', 'notas as note')
                ->where([
                    'tbl_rubros.estado' => 'A',
                ])
                ->get();
        }*/

        return response()->json([
            'values' => $values,
            'status' => '200',
        ], 200);
    }


    public function addValues(Request $request) {

        $values = Value::where('id_criterio', $request->get('criterion'))
        ->where('id_rubro', $request->get('value'))
        ->update([
            'valor_pp' => $request->get('previousPeriod'),
            'valor_pa' => $request->get('currentPeriod'),
        ]);

        return response()->json([
            'status' => '200',
        ], 200);
    }


}
