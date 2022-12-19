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
use App\Models\Business;
use App\Models\LicenseDistribution;
use DB;

class DataEntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getEntryData(Request $request)
    {

        $template = LicenseDistribution::where([
            'id_usuario_asignado' => auth()->user()->id_usuario,
            'id_empresa' => $request->get('id'),
        ])->first();

        LicenseDistribution::where([
            'id_usuario' => $template->id_usuario,
            'id_usuario_asignado' => auth()->user()->id_usuario,
        ])->update([
            'empresa_defecto' => 'N',
        ]);

        LicenseDistribution::where([
            'id_usuario_asignado' => auth()->user()->id_usuario,
            'id_empresa' => $request->get('id'),
        ])->update([
            'empresa_defecto' => 'S',
        ]);


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
            'mes_inicio' => $request->get('month'),
            'anio_inicio' => $request->get('year'),
        ])->first();



        if(!$exist) {

            $startMonth = Carbon::parse($request->get('startMonth'));
            $endMonth = Carbon::parse($request->get('endMonth'));
            $startMonthPeriod = Carbon::parse($request->get('startMonthPeriod'));
            $endMonthPeriod = Carbon::parse($request->get('endMonthPeriod'));

            Criterion::where([
                'id_usuario' => auth()->user()->id_usuario,
                'id_empresa' => $request->get('business'),
            ])->update([
                'activo' => 'I',
            ]);

            $getCriterion = Criterion::create([
                'id_usuario' => auth()->user()->id_usuario,
                'id_empresa' => $request->get('business'),
                'id_periodo' => $request->get('period'),
                'id_moneda' => $request->get('currency'),
                'mes_inicio' => $startMonth->format('m'),
                'anio_inicio' => $startMonth->format('Y'),
                'mes_fin' => $endMonth->format('m'),
                'anio_fin' => $endMonth->format('Y'),
                'mes_inicio_pa' => $startMonthPeriod->format('m'),
                'anio_inicio_pa' => $startMonthPeriod->format('Y'),
                'mes_fin_pa' => $endMonthPeriod->format('m'),
                'anio_fin_pa' => $endMonthPeriod->format('Y'),
                'numero_dias' => $request->get('countDays') + 1,
                'activo' => 'A'
            ]);

            $criterion = $getCriterion->id_criterio;

            $value = Value::where([
                'id_criterio' => $getCriterion->id_criterio,
                'id_empresa' => $request->get('business'),
                'id_usuario' => auth()->user()->id_usuario,
            ])->first();
            // Se trae lista de rubros
            if(!$value) {
                $entry = Entry::where('estado', 'A')->orderBy('id_rubro', 'desc')->get();

                foreach ($entry as $value) {

                    Value::create([
                        'id_criterio' => $getCriterion->id_criterio,
                        'id_rubro' => $value->id_rubro,
                        'id_empresa' => $request->get('business'),
                        'id_usuario' => auth()->user()->id_usuario,
                        'valor_pp' => '0',
                        'valor_pa' => '0',
                        'estado' => 'A'
                    ]);
                }

                if ($request->get('type') == '2') {
                    $entry = Entry::where('estado', 'A')->orderBy('id_rubro', 'desc')->get();
                    $business = Business::select('tipo_empresa', 'id_empresa')->where('id_empresa_padre', $request->get('business'))->get();

                    foreach ($business as $emp) {
                        foreach ($entry as $value) {
                            Value::create([
                                'id_criterio' => $getCriterion->id_criterio,
                                'id_rubro' => $value->id_rubro,
                                'id_empresa' => $emp->id_empresa,
                                'id_usuario' => auth()->user()->id_usuario,
                                'valor_pp' => '0',
                                'valor_pa' => '0',
                                'estado' => 'A'
                            ]);
                        }
                    }

                }
            }

            //}




        } else {


            $criterion = $exist->id_criterio;

            Criterion::where([
                'id_usuario' => auth()->user()->id_usuario,
                'id_empresa' => $request->get('business'),
            ])->update([
                'activo' => 'I',
            ]);


            Criterion::where([
                'id_criterio' => $criterion
            ])->update([
                'activo' => 'A',
            ]);

        }



        return response()->json([
            'status' => '200',
            'criterion' => $criterion,
        ], 200);

    }

    public function getVelues(Request $request) {

        // $criterion = Criterion::select('id_empresa')->where('id_criterio', $request->get('c'))->first();



        if($request->get('c')) {

            $business = Business::select('tbl_empresas.tipo_empresa', 'tbl_distribucion_licencias.id_usuario')->where([
                'tbl_empresas.id_empresa' => $request->get('b'),
                'id_usuario_asignado' => auth()->user()->id_usuario,
                ])
                ->join('tbl_distribucion_licencias', function ($join) {
                    $join->on('tbl_distribucion_licencias.id_empresa', '=', 'tbl_empresas.id_empresa')
                        ->orOn('tbl_distribucion_licencias.id_usuario', '=', 'tbl_empresas.id_usuario');
                })
                ->first();

            if ($business->tipo_empresa == '2') {

                $bu = DB::select('select  id_criterio,tbl_rubros.id_rubro as id, sum(valor_pp) as previousPeriod, sum(valor_pa) as currentPeriod,
                    descripcion as description, nemonico as name, edita_pp as previousEdit, edita_pa as currentEdit, notas as note,
                    tbl_valores.id_empresa as business
                    from    tbl_valores
                    INNER JOIN tbl_rubros on tbl_rubros.id_rubro = tbl_valores.id_rubro
                    Inner join tbl_empresas on tbl_empresas.id_empresa = tbl_valores.id_empresa
                    where  id_criterio = '.$request->get('c').' and tbl_empresas.tipo_empresa = "3"
                    group by tbl_rubros.id_rubro order by tbl_rubros.id_rubro');

                foreach ($bu as $value) {
                    $va = Value::where([
                        'id_criterio' => $request->get('c'),
                        'id_rubro' => $value->id,
                        'id_empresa' => $request->get('b')
                    ])
                    ->update([
                        'valor_pp' => $value->previousPeriod,
                        'valor_pa' => $value->currentPeriod,
                    ]);
                }

            }

            $values = Entry::select('tbl_rubros.id_rubro as id','descripcion as description', 'nemonico as name',
                'valor_pp as previousPeriod', 'valor_pa as currentPeriod', 'edita_pp as previousEdit', 'edita_pa as currentEdit', 'notas as note')
            ->where([
                'tbl_rubros.estado' => 'A',
                'tbl_valores.id_criterio' =>  $request->get('c'),
                'tbl_valores.id_empresa' => $request->get('b'),
                'tbl_valores.id_usuario' => $business->id_usuario,
            ])
            ->leftJoin('tbl_valores', 'tbl_valores.id_rubro', '=', 'tbl_rubros.id_rubro')
            ->orderBy('tbl_rubros.orden', 'asc')
            ->get();

        } else {
            $values = [];
        }



        /*if ($business->tipo_empresa == '2') {

            $values = DB::select('select  id_criterio,tbl_rubros.id_rubro as id, sum(valor_pp) as previousPeriod, sum(valor_pa) as currentPeriod,
                descripcion as description, nemonico as name, edita_pp as previousEdit, edita_pa as currentEdit, notas as note
                from    tbl_valores
                INNER JOIN tbl_rubros on tbl_rubros.id_rubro = tbl_valores.id_rubro
                Inner join tbl_empresas on tbl_empresas.id_empresa = tbl_valores.id_empresa
                where  id_criterio = '.$request->get('c').' and tbl_empresas.tipo_empresa = "3"
                group by tbl_rubros.id_rubro order by tbl_rubros.id_rubro');


        } else {*/

        //}




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
        ->where([
            'id_rubro' => $request->get('value'),
            'id_empresa' => $request->get('business')
        ])
        ->update([
            'valor_pp' => $request->get('previousPeriod') ? $request->get('previousPeriod') : 0,
            'valor_pa' => $request->get('currentPeriod') ? $request->get('currentPeriod') : 0,
        ]);

        return response()->json([
            'status' => '200',
        ], 200);
    }


}
