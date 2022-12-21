<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Indicator;
use App\Models\Criterion;
use App\Models\Value;
use App\Models\Result;
use DB;

class RunProcess extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function run(Request $request) {
        $array = [];
        // Obtenemos la lista de indicadores y criterio
        $indicators = Indicator::select('formula as formulate', 'id_indicador as indicator',
            'formula_mostrar as viewFormulate', 'expresado as expressed', 'nemonico as mnemonic')
            ->where('estado', 'A')
            ->orderBy('orden', 'asc')
            ->get();
        $criterion = Criterion::select('id_usuario as user', 'id_empresa as business', 'numero_dias as countDay')
            ->where('id_criterio', $request->get('criterion'))->first();

        foreach ($indicators as $key => $value) {
            $entry = "";

            // Validamos si existe cantidad de dias periodo
            $separatorVar = "VAR_DIAS_PERIODO";
            if (preg_match("/{$separatorVar}/i", $value->formulate)) {
                $value->formulate = str_replace('VAR_DIAS_PERIODO', $criterion->countDay, $value->formulate);
                $entry .= 'Cantidad dias : ' . $criterion->countDay . ' <br>';
            }

            // Validamos si va a usar variables de indicadores y la reemplzamos
            //if (strpos($value->formulate, 'RES_')) {
                $response_result = Result::select('nemonico as mnemonic','resultado as result', 'nombre as name')
                ->join('tbl_indicadores', 'tbl_indicadores.id_indicador', '=', 'tbl_resultados.id_indicador')
                    ->where([
                        'id_criterio' => $request->get('criterion'),
                        'id_usuario' => $criterion->user,
                        'id_empresa' => $criterion->business,
                    ])
                    ->get();

                    foreach ($response_result as $i => $result) {
                        $value->formulate = str_replace('RES_' . $result->mnemonic, $result->result, $value->formulate);
                        $val[$i] = $value->formulate .'-'. $result->mnemonic;
                        $separatorRes = 'RES_' . $result->mnemonic;
                        if (preg_match("/{$separatorRes}/i", $value->formulate)) {
                            $entry .= $result->name . ': ' . number_format($result->result, 2) . ' <br>';
                        }
                    }
            //}
            // obtenemos los valores y reemplzamos en la formula
            $values = Value::select('nemonico as mnemonic', 'valor_pp as previous', 'valor_pa as current', 'descripcion as description')
                ->where('id_criterio', $request->get('criterion'))
                ->join('tbl_rubros', 'tbl_rubros.id_rubro', '=', 'tbl_valores.id_rubro')
                ->get();

            // rubros utilizados
            $arr = [];
            foreach($values as $index => $item) {
                $ee ="";
                $separatorAnt = 'ANT_' . $item->mnemonic;
                if (preg_match("/{$separatorAnt}/i", $value->formulate)) {
                    $entry .= $item->description . ': ' . number_format($item->previous, 2) . ' <br>';

                }
                $separatorAct = 'ACT_' . $item->mnemonic;
                if (preg_match("/{$separatorAct}/i", $value->formulate)) {
                    $entry .= $item->description . ': ' . number_format($item->current, 2) . ' <br>';
                    $ee = "entro";
                }


                $value->formulate = str_replace('ANT_' . $item->mnemonic, $item->previous, $value->formulate);
                $value->formulate = str_replace('ACT_' . $item->mnemonic, $item->current, $value->formulate);
                $arr[$index] = $value->formulate."-_".$item->mnemonic."_".$entry.":_:".$ee;

            }
            $array[$key] = $arr;
            $result = "";

            try {
                $cadena = '$var = '.$value->formulate.';';
                eval($cadena);
                $result = $var;
            } catch (\Throwable $th) {
                $result = null;
            }

            $response = Result::select('id_criterio as id')
                ->where([
                    'id_criterio' => $request->get('criterion'),
                    'id_indicador' => $value->indicator,
                    'id_usuario' => $criterion->user,
                    'id_empresa' => $criterion->business,
                ])->first();

            if ($response) {
                Result::where([
                    'id_resultado' => $response->id_resultado,
                ])->update([
                    'resultado' => $result,
                    'formula' => $value->viewFormulate,
                    'formula_descifrada' => $value->formulate,
                    'valores' => $entry,
                    'validacion_formula' => $result ? 'O' : 'E'
                ]);
            } else {
                Result::create([
                    'id_criterio' => $request->get('criterion'),
                    'id_indicador' => $value->indicator,
                    'id_usuario' => $criterion->user,
                    'id_empresa' => $criterion->business,
                    'resultado' => $result,
                    'formula' => $value->viewFormulate,
                    'formula_descifrada' => $value->formulate,
                    'valores' => $entry,
                    'validacion_formula' => $result ? 'O' : 'E'
                ]);
            }




        }


        return response()->json([
            'status' => '200',
            'response' =>  $array,
        ], 200);



    }

}
