<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LicenseDistribution;
use App\Models\HistoryPlans;

class LicensesController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function infoPlan(Request $request) {
        $plan = LicenseDistribution::join('tbl_planes', 'tbl_planes.id_plan', 'tbl_distribucion_licencias.id_plan')
            ->where([
                'id_usuario' => auth()->user()->id_usuario,
                'tbl_distribucion_licencias.estado' => 'A',
                'tbl_planes.tipo' => 'P'
            ])->orderBy('tbl_distribucion_licencias.id_usuario', 'desc')
            ->first();
        
        $totalAssigned = LicenseDistribution::where([
                'id_usuario' => auth()->user()->id_usuario,
                'estado' => 'A'
            ])->count();
        
        $totalAcquired = HistoryPlans::where([
            'id_usuario' => auth()->user()->id_usuario,
            'estado' => 'A'
        ])->sum('numero');
            //(select count(*) from tbl_distribucion_licencias where tbl_distribucion_licencias.id_usuario = tbl_usuarios.id_usuario) as countLicense

        return  response()->json([
            'status' => '200',
            'plan' =>  $plan ? 'Plan Coorporativo' : 'Plan Gratuito',
            'totalAssigned' => $totalAssigned,
            'totalAcquired' => $totalAcquired
        ], 200);
        
    }
}
