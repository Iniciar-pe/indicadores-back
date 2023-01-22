<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderControoler extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function add(Request $request)
    {

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
                'id_plan' => $value->id
            ]);
        }

        return response()->json([
            'status' => '200',
            'message' => 'Registration registered correctly',
        ], 200);

    }




}
