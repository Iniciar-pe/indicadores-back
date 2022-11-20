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

        $periodo = PeriodCalculation::select('id_periodo as id', 'nombre_periodo as name', 'estado as status')
            ->where('estado', 'A')
            ->orderBy('id_periodo', 'asc')->get();

        $currency = Currency::select('id_moneda as id', 'simbolo as symbol', 'descripcion as description', 'estado as status')
            ->where('estado', 'A')
            ->orderBy('id_moneda', 'asc')->get();

        $criterion = Criterion:: select('id_criterio as id', 'id_periodo as period', 'id_moneda as currency', 'mes_inicio as startMonth',
            'anio_inicio as startYear', 'mes_fin as endMonth', 'anio_fin as endYear', 'mes_inicio_pa as startMonthPeriod',
            'anio_inicio_pa as startYearPeriod', 'mes_fin_pa as endMonthPeriod', 'anio_fin_pa as endYearPeriod')
            ->where([
                'id_usuario' => auth()->user()->id_usuario,
                'activo' => 'A'
            ])->orderBy('id_criterio', 'desc')
            ->first();

        $Business = \App\Models\Business::select('id_empresa as id', 'id_empresa_padre as indicator')
            ->where('id_usuario', auth()->user()->id_usuario)
            ->where('estado', 'A')
            ->orderBy('id_empresa', 'asc')
            ->first();

        return response()->json([
            'status' => '200',
            'period' => $periodo,
            'currency' => $currency,
            'criterion' => $criterion,
            'business' => $Business->id,
            'indicator' => $Business->indicator
        ], 200);
    }

    public function addEntryData(Request $request) {

        $criterion = "";

        $exist = Criterion::where([
            'id_usuario' => auth()->user()->id_usuario,
            'id_empresa' => $request->get('business'),
            'id_periodo' => $request->get('period'),
            'id_moneda' => $request->get('currency'),
            'mes_inicio' => $request->get('month'),
            'anio_inicio' => $request->get('year'),
        ])->first();

        $criterion = $exist->id_criterio;

        if(!$exist) {
            $period = PeriodCalculation::where('id_periodo', $request->get('period'))->first();

            $date = Carbon::parse($request->get('year').'-'.$request->get('month').'-01');
            $addMonth = $date->addMonths($period->cantidad_meses);

            $dateStart = Carbon::parse($request->get('year').'-'.$request->get('month').'-01');
            $startPa = $dateStart->subMonths($period->cantidad_meses +1);

            $endPa = Carbon::parse($request->get('year').'-'.$request->get('month').'-01');
            $endMonth = $endPa->subMonth();


            //$end_date = date("Y-m-t", strtotime($addMonth->format('Y-m-d')));
            //Establecer la fecha de inicio
            /*$end_date = Carbon::parse(date("Y-m-t", strtotime($addMonth->format('Y').'-'.$addMonth->format('m').'-01')));
            $start_date = Carbon::parse('Ymd' , $request->get('year').'-'.$request->get('month').'-01');
            $endDate = Carbon::parse('Ymd' , $addMonth->format('Y').'-'.$addMonth->format('m').'-'.$end_date->format('d'));
    */

            $criterion = Criterion::create([
                'id_usuario' => auth()->user()->id_usuario,
                'id_empresa' => $request->get('business'),
                'id_periodo' => $request->get('period'),
                'id_moneda' => $request->get('currency'),
                'mes_inicio' => $request->get('month'),
                'anio_inicio' => $request->get('year'), //
                'mes_fin' => $addMonth->format('m'),
                'anio_fin' => $addMonth->format('Y'),
                'mes_inicio_pa' => $startPa->format('m'),
                'anio_inicio_pa' => $startPa->format('Y'),
                'mes_fin_pa' => $endMonth->format('m'),
                'anio_fin_pa' => $endMonth->format('Y'),
                'numero_dias' => $endMonth->format('Y'),
                'activo' => 'A'
            ]);

            $criterion = $criterion->id_criterio;
        }



        return response()->json([
            'status' => '200',
            'criterion' => $criterion,
        ], 200);

    }

    public function getVelues(Request $request) {

        $values = Entry::select('tbl_rubros.id_rubro as id','descripcion as description', 'nemonico as name',
             'valor_pp as currentPeriod', 'valor_pa as previousPeriod', 'edita_pp as previousEdit', 'edita_pa as currentEdit', 'notas as note')
            ->where([
                'tbl_rubros.estado' => 'A',
                'tbl_valores.id_criterio' =>  $request->get('criterion'),
            ])
            ->leftJoin('tbl_valores', 'tbl_valores.id_rubro', '=', 'tbl_rubros.id_rubro')
            ->get();

        return response()->json([
            'values' => $values,
            'status' => '200',
        ], 200);
    }


    public function addValues(Request $request) {

        foreach ($request->get('values') as $value) {

            Value::create([
                'id_criterio' => $request->get('criterion'),
                'id_rubro' => $value['id'],
                'valor_pp' => $value['currentPeriod'],
                'valor_pa' => $value['previousPeriod'],
                'estado' => 'A'
            ]);
        }

        return response()->json([
            'status' => '200',
        ], 200);
    }


}
