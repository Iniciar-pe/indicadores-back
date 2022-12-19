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
            ->where('estado', 'A')->get();
        $criterion = Criterion::select('id_usuario as user', 'id_empresa as business', 'numero_dias as countDay')
            ->where('id_criterio', $request->get('criterion'))->first();

        foreach ($indicators as $key => $value) {

            // Validamos si existe cantidad de dias periodo
            if (strpos($value->formulate, 'VAR_DIAS_PERIODO')) {
                $value->formulate = str_replace('VAR_DIAS_PERIODO', $criterion->countDay, $value->formulate);
            }

            // Validamos si va a usar variables de indicadores y la reemplzamos
            // if (strpos($value->formulate, 'RES_')) {
                $response_result = Result::select('nemonico as mnemonic','resultado as result')
                ->join('tbl_indicadores', 'tbl_indicadores.id_indicador', '=', 'tbl_resultados.id_indicador')
                    ->where([
                        'id_criterio' => $request->get('criterion'),
                        'id_usuario' => $criterion->user,
                        'id_empresa' => $criterion->business,
                    ])
                    ->get();

                    $val = [];
                    foreach ($response_result as $i => $result) {
                        $value->formulate = str_replace('RES_' . $result->mnemonic, $result->result, $value->formulate);
                        $val[$i] = $value->formulate .'-'. $result->mnemonic;
                    }
            // }
                    $array[$key] = $val;
            // obtenemos los valores y reemplzamos en la formula
            $values = Value::select('nemonico as mnemonic', 'valor_pp as previous', 'valor_pa as current', 'descripcion as description')
                ->where('id_criterio', $request->get('criterion'))
                ->join('tbl_rubros', 'tbl_rubros.id_rubro', '=', 'tbl_valores.id_rubro')
                ->get();

            // rubros utilizados
            $entry = "";

            foreach($values as $index => $item) {

                if (strpos($value->formulate, 'ANT_' . $item->mnemonic)) {
                    $entry .= $item->description . ': ' . $item->previous . ' <br>';

                }

                if (strpos($value->formulate, 'ACT_' . $item->mnemonic)) {
                    $entry .= $item->description . ': ' . $item->current . ' <br>';

                }
                $value->formulate = str_replace('ANT_' . $item->mnemonic, $item->previous, $value->formulate);
                $value->formulate = str_replace('ACT_' . $item->mnemonic, $item->current, $value->formulate);

            }

            $result = "";

            try {
                $cadena = '$var = '.$value->formulate.';';
                eval($cadena);
                $result = $var;

                if($value->expressed == '2') {
                    $result = $result * 100;
                }
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
