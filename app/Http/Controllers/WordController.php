<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Criterion;
use App\Models\Indicator;
use Carbon\Carbon;
use App\Models\Values;


class WordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function downloadWord(Request $request) {

        $data = Criterion::select('nombre_empresa as name', 'tbl_periodos_calculo.nombre_periodo as description', 'mes_inicio as moth',
            'anio_fin as  year', 'mes_fin as mothEnd', 'anio_fin as yearEnd', 'mes_inicio_pa as moth_pa', 'anio_fin_pa as  year_pa',
            'mes_fin_pa as mothEnd_pa', 'anio_fin_pa as yearEnd_pa', 'tbl_monedas.descripcion as name_currency', 'simbolo as symbol',
            'numero_dias as days', 'id_criterio')
            ->where([
                'tbl_criterios.id_empresa' => $request->get('e'),
            ])
            ->join('tbl_empresas', 'tbl_empresas.id_empresa', '=', 'tbl_criterios.id_empresa')
            ->join('tbl_periodos_calculo', 'tbl_periodos_calculo.id_periodo', '=', 'tbl_criterios.id_periodo')
            ->join('tbl_monedas', 'tbl_monedas.id_moneda', '=', 'tbl_criterios.id_moneda')
            ->first();

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = Carbon::parse($data->year . '-' . $data->moth . '-01');
        $mes = $meses[($fecha->format('n')) - 1];

        $number = cal_days_in_month(CAL_GREGORIAN, $data->mothEnd, $data->yearEnd);
        $fecha1 = Carbon::parse($data->yearEnd . '-' . $data->mothEnd . '-' . $number);
        $mes1 = $meses[($fecha->format('n')) - 1];

        $fecha2 = Carbon::parse($data->year_pa . '-' . $data->moth_pa . '-01');
        $mes2 = $meses[($fecha->format('n')) - 1];

        $number3 = cal_days_in_month(CAL_GREGORIAN, $data->mothEnd_pa, $data->yearEnd_pa);
        $fecha3 = Carbon::parse($data->yearEnd_pa . '-' . $data->mothEnd_pa . '-' . $number3);
        $mes3 = $meses[($fecha->format('n')) - 1];


        try {

            $period_actual = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y') .' a ' . $fecha1->format('d') . ' de ' . $mes1 . ' de ' . $fecha1->format('Y');
            $period_anterior = $fecha2->format('d') . ' de ' . $mes2 . ' de ' . $fecha2->format('Y') .' a ' . $fecha3->format('d') . ' de ' . $mes3 . ' de ' . $fecha3->format('Y');

            $template = new \PhpOffice\PhpWord\TemplateProcessor(documentTemplate: 'app/RESUMEN_EJECUTIVO.docx');

            $values = Values::select('nemonico as denomic', 'valor as value', 'texto as text', 'formula as formulate', 'padre as father',
                    'id_variable as id')
                    ->where('padre',  null)
                    ->get();

                foreach ($values as $i => $item) {

                    $indicator = Indicator::select('tbl_indicadores.nemonico as denomic', 'resultado as result', 'expresado as voiced')
                            ->join('tbl_resultados', 'tbl_resultados.id_indicador', '=', 'tbl_indicadores.id_indicador')
                            ->where([
                                'id_criterio' => $data->id_criterio,
                                'id_empresa' => $request->get('e'),
                                'tbl_indicadores.nemonico' => $item->value
                            ])
                            ->orderBy('orden', 'asc')
                            ->first();

                    $result = $indicator->voiced == '2' ? ((float)$indicator->result * 100) : (float)$indicator->result;
                    $texto = str_replace($item->value, (float)$result, $item->formulate);


                    $test = eval("return $texto;");
                    if ($test) {
                        if ($item->denomic != '1') {
                            $template->setValue(search: $item->denomic, replace: $item->text);
                        } else {

                            $valuesfather = Values::select('nemonico as denomic', 'valor as value', 'texto as text', 'formula as formulate', 'padre as father')
                            ->where('padre', $item->id)
                            ->get();

                            foreach ($valuesfather as $e => $response) {

                                $valuesResponse = Indicator::select('tbl_indicadores.nemonico as denomic', 'resultado as result', 'expresado as voiced')
                                ->join('tbl_resultados', 'tbl_resultados.id_indicador', '=', 'tbl_indicadores.id_indicador')
                                ->where([
                                    'id_criterio' => $data->id_criterio,
                                    'id_empresa' => $request->get('e'),
                                    'tbl_indicadores.nemonico' => $response->value
                                ])
                                ->orderBy('orden', 'asc')
                                ->first();

                                $result = $valuesResponse->voiced == '2' ? ((float)$valuesResponse->result * 100) : (float)$valuesResponse->result;
                                $text = str_replace($response->value, (float)$result, $response->formulate);

                                $tes = eval("return $text;");
                                if ($tes) {
                                    $template->setValue(search: $response->denomic, replace: $response->text);
                                }

                            }

                        }
                    }



                }

            $response = Indicator::select('tbl_indicadores.nemonico as denomic', 'resultado as result', 'expresado as voiced')
                ->join('tbl_resultados', 'tbl_resultados.id_indicador', '=', 'tbl_indicadores.id_indicador')
                ->where([
                    'id_criterio' => $data->id_criterio,
                    'id_empresa' => $request->get('e'),
                ])
                ->orderBy('orden', 'asc')
                ->get();

            foreach ($response as $key => $value) {
                //$value->result = (int)str_replace((string)$value->result, ",", ".");
                $result = $value->voiced == '2' ? ((float)$value->result * 100) . '%' : (float)$value->result;
                $template->setValue(search: $value->denomic, replace: number_format((float)$result, 2, ',', ' '));
            }

            $template->setValue(search: 'nombre_empresa', replace: $data->name);
            $template->setValue(search: 'periodo_nombre', replace: $data->description);
            $template->setValue(search: 'periodo_actual', replace: $period_actual);
            $template->setValue(search: 'periodo_anterior', replace: $period_anterior);
            $template->setValue(search: 'dias', replace: $data->days);
            $template->setValue(search: 'moneda', replace: $data->name_currency);
            $template->setValue(search: 'simb_modeda', replace: $data->symbol);

            $tempFile = tempnam(sys_get_temp_dir(), prefix: 'PHPWord');
            $template->saveAs($tempFile);

            $headers = [
                "Content-Type: application/octet-stream",
            ];

            return response()->download($tempFile, name: 'document.docx')->deleteFileAfterSend(shouldDelete: true);


        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            return back($e->getCode());
        }

    }


}
