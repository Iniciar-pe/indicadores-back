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
        $e = explode('-', $request->get('date'));
        $numPedido = $e[0] . $e[1];

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
            'numero_pedido' => $numPedido
        ]);




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

            UserPlan::create([
                'id_usuario' => auth()->user()->id_usuario,
                'id_plan' => $value->id,
                'estado' => 'A',
            ]);

            HistoryPlans::create([
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
            'order' => $numPedido . $order->id_pedido
        ], 200);

    }




}
