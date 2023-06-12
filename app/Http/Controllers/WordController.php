<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Criterion;
use App\Models\Indicator;
use Carbon\Carbon;
use App\Models\Values;
use PDF;


class WordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function downloadWord(Request $request) {

        $response = Criterion::select('nombre_empresa as name', 'tbl_periodos_calculo.singular as singular', 'mes_inicio as moth',
            'anio_fin as  year', 'mes_fin as mothEnd', 'anio_fin as yearEnd', 'mes_inicio_pa as moth_pa', 'anio_fin_pa as  year_pa',
            'mes_fin_pa as mothEnd_pa', 'anio_fin_pa as yearEnd_pa', 'tbl_monedas.descripcion as name_currency', 'simbolo as symbol',
            'numero_dias as days', 'id_criterio', 'tbl_periodos_calculo.plural as plural')
            ->where([
                'tbl_criterios.id_empresa' => $request->get('e'),
            ])
            ->join('tbl_empresas', 'tbl_empresas.id_empresa', '=', 'tbl_criterios.id_empresa')
            ->join('tbl_periodos_calculo', 'tbl_periodos_calculo.id_periodo', '=', 'tbl_criterios.id_periodo')
            ->join('tbl_monedas', 'tbl_monedas.id_moneda', '=', 'tbl_criterios.id_moneda')
            ->first();

        $period_actual = $this->formatDate($response->year, $response->moth, false) .' al ' . $this->formatDate($response->yearEnd, $response->mothEnd, true);
        $period_anterior = $this->formatDate($response->year_pa, $response->moth_pa, false) .' al ' . $this->formatDate($response->yearEnd_pa, $response->mothEnd_pa, true);

        $indicador = Indicator::select('tbl_indicadores.nemonico as denomic', 'resultado as result', 'expresado as voiced', 'nombre as name',
        'tipo as type')
            ->join('tbl_resultados', 'tbl_resultados.id_indicador', '=', 'tbl_indicadores.id_indicador')
            ->where([
                'id_criterio' => $response->id_criterio,
                'id_empresa' => $request->get('e'),
                'tipo' => '2',
            ])
            ->orderBy('orden', 'asc')
            ->get();

        $indicadorType = Indicator::select('tbl_indicadores.nemonico as denomic', 'resultado as result', 'expresado as voiced', 'nombre as name',
            'tipo as type')
            ->join('tbl_resultados', 'tbl_resultados.id_indicador', '=', 'tbl_indicadores.id_indicador')
            ->where([
                'id_criterio' => $response->id_criterio,
                'id_empresa' => $request->get('e'),
                'tipo' => '1',
            ])
            ->orderBy('orden', 'asc')
            ->get();


        $data = [
            'nombre_empresa' => $response->name,
            'periodo_actual' => $period_actual,
            'periodo_anterior' => $period_anterior,
            'periodo_nombre' => $response->singular,
            'dias' => $response->days,
            'moneda' => $response->name_currency,
            'simb_modeda' => $response->symbol,
            'indicador' => $indicador,
            'indicadorType' => $indicadorType,
            'periodo_nombre_plural' => $response->plural
        ];

        $pdf = PDF::loadView('pruebaparapdf', compact('data'));
        //return  $pdf->stream('prueba.pdf');
        return $pdf->download('pruebapdf.pdf');


        /*
        $data = Criterion::select('nombre_empresa as name', 'tbl_periodos_calculo.singular as singular', 'mes_inicio as moth',
            'anio_fin as  year', 'mes_fin as mothEnd', 'anio_fin as yearEnd', 'mes_inicio_pa as moth_pa', 'anio_fin_pa as  year_pa',
            'mes_fin_pa as mothEnd_pa', 'anio_fin_pa as yearEnd_pa', 'tbl_monedas.descripcion as name_currency', 'simbolo as symbol',
            'numero_dias as days', 'id_criterio', 'tbl_periodos_calculo.plural as plural')
            ->where([
                'tbl_criterios.id_empresa' => $request->get('e'),
            ])
            ->join('tbl_empresas', 'tbl_empresas.id_empresa', '=', 'tbl_criterios.id_empresa')
            ->join('tbl_periodos_calculo', 'tbl_periodos_calculo.id_periodo', '=', 'tbl_criterios.id_periodo')
            ->join('tbl_monedas', 'tbl_monedas.id_moneda', '=', 'tbl_criterios.id_moneda')
            ->first();

        try {

            $period_actual = $this->formatDate($data->year, $data->moth, false) .' al ' . $this->formatDate($data->yearEnd, $data->mothEnd, true);
            $period_anterior = $this->formatDate($data->year_pa, $data->moth_pa, false) .' al ' . $this->formatDate($data->yearEnd_pa, $data->mothEnd_pa, true);

            $template = new \PhpOffice\PhpWord\TemplateProcessor(documentTemplate: 'app/RESUMEN_EJECUTIVO.docx');

            // Tabla indicadores y resultados
            $this->indicatorFormat($template, $data->id_criterio, $request->get('e'));

            $template->setValue(search: 'nombre_empresa', replace: $data->name);
            $template->setValue(search: 'periodo_nombre', replace: $data->singular);
            $template->setValue(search: 'periodo_nombre_plural', replace: $data->plural);
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
        */

    }

    protected function formatDate($year, $moth, $inNumber) {

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $number = $inNumber ? cal_days_in_month(CAL_GREGORIAN, $moth, $year) : '01';

        $fecha = Carbon::parse($year . '-' . $moth . '-' . $number);

        return $fecha->format('d') . ' de ' . $meses[($fecha->format('n')) - 1] . ' del ' . $fecha->format('Y');

    }

    protected function indicatorFormat($template, $id_criterio, $id_empresa) {

        $response = Indicator::select('tbl_indicadores.nemonico as denomic', 'resultado as result', 'expresado as voiced')
            ->join('tbl_resultados', 'tbl_resultados.id_indicador', '=', 'tbl_indicadores.id_indicador')
            ->where([
                'id_criterio' => $id_criterio,
                'id_empresa' => $id_empresa,
            ])
            ->orderBy('orden', 'asc')
            ->get();

        foreach ($response as $key => $value) {

            $result = $value->voiced == '2' ? (float)$value->result * 100 : (float)$value->result;
            $template->setValue(search: $value->denomic, replace: number_format((float)$result, 2) . ($value->voiced == '2' ? '%' : ''));

            // Tabla valores
            $this->valuesFormat($template, $value, $id_criterio, $id_empresa);

        }
    }

    protected function valuesFormat($template, $value, $id_criterio, $id_empresa) {

        $values = Values::select('nemonico as denomic', 'valor as value', 'texto as text', 'formula as formulate',
            'valor_secundario as secondary')->get();

        foreach ($values as $i => $item) {

            if ($item->secondary != null) {
                $response = Indicator::select('tbl_indicadores.nemonico as denomic', 'resultado as result', 'expresado as voiced')
                    ->join('tbl_resultados', 'tbl_resultados.id_indicador', '=', 'tbl_indicadores.id_indicador')
                    ->where([
                        'id_criterio' => $id_criterio,
                        'id_empresa' => $id_empresa,
                        'nemonico' => $item->secondary,
                    ])
                    ->orderBy('orden', 'asc')
                    ->first();

                $result = $response->voiced == '2' ? ((float)$response->result * 100) : (float)$response->result;
                $item->formulate = str_replace($item->secondary, (float)$result, $item->formulate);

            }

            $result = $value->voiced == '2' ? ((float)$value->result * 100) : (float)$value->result;

            $texto = str_replace($item->value, (float)$result, $item->formulate);
            $test = eval("return $texto;");
            if ($test) {
                $template->setValue(search: $item->denomic, replace: $item->text);
            }

        }

    }


}
