<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Indicator;
use App\Models\Template;
use App\Models\LicenseDistribution;

class IndicatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get(Request $request)
    {
        return response()->json([
            'status' => '200',
            'indicator' => Indicator::orderBy('orden', 'asc')->get(),
            'template' => Template::orderBy('id_plantilla', 'desc')->get()
        ], 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:1',
            'formula' => 'required|string|max:490',
            'public' => 'required|string|max:1',
            //'id_payroll' => 'required|integer|max:11',
            'order' => 'required|integer',
            'expressed' => 'required|string|max:1',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $indicador = $this->incrementing();
        //dd($indicador);

        $indicador = Indicator::create([
            'id_indicador' => $indicador ? $indicador->id_indicador + 1 : '1',
            'descripcion' => $request->get('description'),
            'nombre' => $request->get('name'),
            'formula' => $request->get('formula'),
            'publico' => $request->get('public'),
            'id_plantilla' => $this->idTemplate()->id_plantilla,
            'orden' => $request->get('order'),
            'expresado' => $request->get('expressed'),
            'estado' => $request->get('status'),
            'tipo' => $request->get('type'),
            'formula_mostrar' => $request->get('view'),
            'nemonico' => $request->get('nemonico'),
            'detalle_resultado' => $request->get('detalle_resultado'),
        ]);

        return response()->json([
            'status' => '200',
            'message' => 'Registration registered correctly',
        ], 200);

    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:1',
            'formula' => 'required|string|max:490',
            'public' => 'required|string|max:1',
            //'id_payroll' => 'required|integer|max:11',
            'order' => 'required|integer',
            'expressed' => 'required|string|max:1',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $indicador = Indicator::find($request->get('id'));
        $indicador->descripcion = $request->get('description');
        $indicador->nombre = $request->get('name');
        $indicador->formula = $request->get('formula');
        $indicador->publico = $request->get('public');

        $indicador->id_plantilla = $this->idTemplate()->id_plantilla;
        $indicador->orden = $request->get('order');
        $indicador->expresado = $request->get('expressed');
        $indicador->estado = $request->get('status');
        $indicador->tipo = $request->get('type');
        $indicador->formula_mostrar = $request->get('view');
        $indicador->tipo = $request->get('type');
        $indicador->nemonico = $request->get('nemonico');
        $indicador->save();
        return response()->json([
            'status' => '200',
            'message' => 'Updated record correctly',
        ], 200);

    }

    public function delete(Request $request)
    {

        $entry = Indicator::where('id_indicador', $request->get('id'))->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Record deleted correctly',
        ], 200);
    }

    private function upload(Request $request)
    {
        if(!$request->hasFile('icono') && !$request->file('icono')->isValid()) {
            return esponse()->json('{"error": "ddd"}');
        }

        try {

            $file = $request->file('icono')->getClientOriginalName();
            Storage::disk('local')->put($file, file_get_contents($request->file('icono')));
            //$name_file = Storage::disk('local')->url($file);
            return $file;
        } catch (\Throwable $th) {
            return response()->json($th);
        }

    }

    private function incrementing()
    {
        return Indicator::orderBy('id_indicador', 'desc')->first();
    }

    private function idTemplate() {
        return Template::where('pdefault', 'S')->first();
    }

    public function getRatios(Request $request) {

        $template = LicenseDistribution::where([
            'id_usuario_asignado' => auth()->user()->id_usuario,
            'id_empresa' => $request->get('business'),
        ])->first();

        LicenseDistribution::where([
            'id_usuario' => $template->id_usuario,
            'id_usuario_asignado' => auth()->user()->id_usuario,
        ])->update([
            'empresa_defecto' => 'N',
        ]);

        LicenseDistribution::where([
            'id_usuario_asignado' => auth()->user()->id_usuario,
            'id_empresa' => $request->get('business'),
        ])->update([
            'empresa_defecto' => 'S',
        ]);

        $default = \App\Models\LicenseDistribution::select('tbl_distribucion_licencias.id_usuario as user',
        'tbl_distribucion_licencias.id_empresa as business', 'id_criterio as id', 'empresa_defecto as default',
        'mes_inicio as startMonth', 'anio_inicio as startYear', 'mes_fin as endMonth', 'anio_fin as endYear',
        'mes_inicio_pa as startMonthPrevious', 'anio_inicio_pa as startYearPrevious', 'mes_fin_pa as endMonthPrevious', 'anio_fin_pa as endYearPrevious',
        'id_periodo as period', 'numero_dias as countDays', 'simbolo as symbol')
            ->where([
                'id_usuario_asignado' => auth()->user()->id_usuario,
                'tbl_distribucion_licencias.id_empresa' => $request->get('business'),
                'tbl_criterios.activo' => 'A',
            ])
            ->join('tbl_criterios', function ($join) {
                $join->on('tbl_criterios.id_empresa', '=', 'tbl_distribucion_licencias.id_empresa')
                    ->on('tbl_criterios.id_usuario', '=', 'tbl_distribucion_licencias.id_usuario');
            })
            ->join('tbl_monedas', 'tbl_monedas.id_moneda', '=', 'tbl_criterios.id_moneda')
            ->orderBy('tbl_criterios.id_criterio', 'desc')
            ->first();

        $ratios = Indicator::select('id_resultado as id', 'nombre as name', 'descripcion as description', 'tbl_resultados.formula',
        'resultado as result', 'valores as value', 'detalle_resultado as detailResult')
        ->where([
            'tbl_resultados.id_criterio' => $default->id,
            'tbl_resultados.id_usuario' => $default->user,
            'tbl_resultados.id_empresa' => $request->get('business'),
            'tipo' => $request->get('type')
        ])
        ->join('tbl_resultados', 'tbl_resultados.id_indicador', '=', 'tbl_indicadores.id_indicador')
        ->orderBy('orden', 'asc')
        ->get();

        return response()->json([
            'status' => '200',
            'default' => $default,
            'ratios' => $ratios
        ], 200);

    }

}
