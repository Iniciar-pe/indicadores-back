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

        $criterion = Criterion::select('id_usuario as user', 'id_empresa as business', 'numero_dias as countDay')
            ->where('id_criterio', $request->get('criterion'))->first();


        Result::select('id_criterio as id')
        ->where([
            'id_criterio' => $request->get('criterion'),
            'id_usuario' => $criterion->user,
            'id_empresa' => $criterion->business,
        ])->delete();

        // Obtenemos la lista de indicadores y criterio
        $indicators = Indicator::select('formula as formulate', 'id_indicador as indicator',
            'formula_mostrar as viewFormulate', 'expresado as expressed', 'nemonico as mnemonic',
            'lista_variables as listValues')
            ->where('estado', 'A')
            ->orderBy('orden', 'asc')
            ->get();


        foreach ($indicators as $key => $value) {

            // Validamos si existe cantidad de dias periodo
            if (strpos($value->formulate, 'VAR_DIAS_PERIODO')) {
                $value->formulate = str_replace('VAR_DIAS_PERIODO', $criterion->countDay, $value->formulate);
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
                    }
            //}
            // obtenemos los valores y reemplzamos en la formula
            $values = Value::select('nemonico as mnemonic', 'valor_pp as previous', 'valor_pa as current', 'descripcion as description')
                ->where('id_criterio', $request->get('criterion'))
                ->join('tbl_rubros', 'tbl_rubros.id_rubro', '=', 'tbl_valores.id_rubro')
                ->get();

            // rubros utilizados
            foreach($values as $index => $item) {

                $value->formulate = str_replace('ANT_' . $item->mnemonic, $item->previous, $value->formulate);
                $value->formulate = str_replace('ACT_' . $item->mnemonic, $item->current, $value->formulate);

            }

            $result = "";

            try {
                $cadena = '$var = '.$value->formulate.';';
                eval($cadena);
                $result = $var;
            } catch (\Throwable $th) {
                $result = null;
            }

            Result::create([
                'id_criterio' => $request->get('criterion'),
                'id_indicador' => $value->indicator,
                'id_usuario' => $criterion->user,
                'id_empresa' => $criterion->business,
                'resultado' => $result,
                'formula' => $value->viewFormulate,
                'formula_descifrada' => $value->formulate,
                'validacion_formula' => $result ? 'O' : 'E'
            ]);

        }

        $response = Indicator::select('id_resultado as id', 'lista_variables as listValues', 'tbl_indicadores.nemonico as denomic',
        'resultado as result', 'nombre as name')
            ->join('tbl_resultados', 'tbl_resultados.id_indicador', '=', 'tbl_indicadores.id_indicador')
            ->where('estado', 'A')
            ->orderBy('orden', 'asc')
            ->get();
        $entro = [];

        foreach ($response as $key => $value) {
            $entry = "";
            $listValues = explode(",", $value->listValues);

            foreach ($listValues as $i => $item) {


                // Actual
                $separatorAct = 'ACT_';
                if (preg_match("/{$separatorAct}/i", $item)) {
                    $valueAct = explode("ACT_", $item);
                    $valuesAct = Value::select('valor_pa as current', 'descripcion as description')
                        ->where([
                            'nemonico' => $valueAct[1],
                            'id_criterio' => $request->get('criterion')
                        ])
                        ->join('tbl_rubros', 'tbl_rubros.id_rubro', '=', 'tbl_valores.id_rubro')
                        ->first();
                    $entry .= $valuesAct->description . ': ' . number_format($valuesAct->current, 2) . ' (Periodo actual) <br>';
                }

                $separatorAnt = 'ANT_';
                if (preg_match("/{$separatorAnt}/i", $item)) {
                    $valueANT = explode("ANT_", $item);
                    $valuesANT = Value::select('valor_pp as previous', 'descripcion as description')
                        ->where([
                            'nemonico' => $valueANT[1],
                            'id_criterio' => $request->get('criterion')
                        ])
                        ->join('tbl_rubros', 'tbl_rubros.id_rubro', '=', 'tbl_valores.id_rubro')
                        ->first();
                    $entry .= $valuesANT->description . ': ' . number_format($valuesANT->previous, 2) . ' (Periodo anterior) <br>';
                }

                $separatorVar = 'VAR_';
                if (preg_match("/{$separatorVar}/i", $item)) {
                    $entry .= 'Cantidad dias : ' . $criterion->countDay . ' <br>';
                }

                $separatorRes = 'RES_';
                if (preg_match("/{$separatorRes}/i", $item)) {
                    $entry .= $value->name . ': ' . number_format($value->result, 2) . ' <br>';
                }


            }

            Result::where([
                'id_resultado' => $value->id,
            ])->update([
                'valores' => $entry,
            ]);


        }

        return response()->json([
            'status' => '200',
            'data' => $entry
        ], 200);



    }

}
