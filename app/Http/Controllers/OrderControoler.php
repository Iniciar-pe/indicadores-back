<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\UserPlan;
use App\Models\HistoryPlans;

class OrderControoler extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function add(Request $request)
    {

        $estado_pago = $request->get('method') == '1' ? '1' : '0';
        $order = Order::create([
            'id_usuario' => auth()->user()->id_usuario,
            'nombre' => $request->get('name'),
            'telefono' => $request->get('phone'),
            'direccion' => $request->get('address'),
            'pais' => $request->get('country'),
            'ciudad' => $request->get('city'),
            'codigo_postal' => $request->get('code'),
            'fecha' => $request->get('date'),
            'hora' => $request->get('hour'),
            'importe' => $request->get('total'),
            'metodo_de_pago' => $request->get('method'),
            'repuesta_pago' => $request->get('response'),
            'estado_pago' => $estado_pago,
        ]);

        $order->numero_pedido = '#' . $order->id_pedido;
        $order->save();


        $json = json_decode($request->detail);

        foreach ($json as $key => $value) {
            // $response = $value->json();
            OrderDetail::create([
                'id_pedido' => $order->id_pedido,
                'orden' => $key,
                'cantidad' => $value->mount,
                'precio_unitario' => $value->value,
                'subtotal' => $value->price,
                'id_plan' => $value->id,
                'fecha_inicio' => $value->date,
                'fecha_fin' => $value->dateEnd,
                'id_periodo_plan' => $value->selectedPeriod
            ]);

            $userPlan = UserPlan::where([
                'id_usuario' => auth()->user()->id_usuario,
                'id_plan' => $value->id,
            ])->first();

            if ($userPlan) {
                $userPlan->estado = 'A';
                $userPlan->save();
            } else {
                UserPlan::create([
                    'id_usuario' => auth()->user()->id_usuario,
                    'id_plan' => $value->id,
                    'estado' => 'A',
                ]);
            }

            $id_historial = $this->incrementing($value->selectedPeriod)->id_historial;

            HistoryPlans::create([
                'id_historial' => $id_historial ? $id_historial + 1 : 1,
                'id_periodo_plan' => $value->selectedPeriod,
                'id_usuario' => auth()->user()->id_usuario,
                'fecha_inicio' => $value->date,
                'fecha_fin' => $value->dateEnd,
                'numero' => $value->mount,
                'estado' => 'A',
            ]);

        }

        return response()->json([
            'status' => '200',
            'message' => 'Registration registered correctly',
            'order' => $order->numero_pedido,
            'total' => $request->get('total')
        ], 200);

    }

    private function incrementing($selectedPeriod)
    {
        return HistoryPlans::orderBy('id_historial', 'desc')->first();
    }

    public function getOrders(Request $request) {
        $order = Order::select('numero_pedido as pedido', 'fecha as date', 'tbl_planes.nombre as license',
            'cantidad as amount', 'subtotal as total', 'estado_pago as status')
            ->where([
                'id_usuario' => auth()->user()->id_usuario,
            ])->join('tbl_pedido_detalle', 'tbl_pedido_detalle.id_pedido', '=', 'tbl_pedidos.id_pedido')
            ->join('tbl_planes', 'tbl_planes.id_plan', '=', 'tbl_pedido_detalle.id_plan')
            ->orderBy('tbl_pedidos.id_pedido', 'desc')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'Registration registered correctly',
            'orders' => $order
        ], 200);
    }

}
