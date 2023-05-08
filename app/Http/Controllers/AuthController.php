<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LicenseDistribution;
use App\Models\Plan;
use App\Models\Business;
use App\Models\HistoryPlans;
use App\Models\UserPlan;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Http;
use App\Models\Donate;
use App\Models\PlanPeriod;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use App\Models\Order;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'login',
            'register',
            'loginSocial',
            'loginLn',
            'sendPassword',
            'changePassword'
        ]
    ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => '400',
                'errors' => '{"email":["Email y/o Contraseña es incorrecto."]}'
            ], 400);

        }

        if (auth()->user()->estado == 'I') {
            return response()->json([
                'status' => '400',
                'errors' => '{"email":["Su cuenta esta inactiva."]}'
            ], 400);
        }

        if (auth()->user()->tipo == 'U') {
            $license = LicenseDistribution::where([
                'id_usuario_asignado' => auth()->user()->id_usuario,
            ])
            ->join('tbl_usuarios', 'tbl_usuarios.id_usuario', '=', 'tbl_distribucion_licencias.id_usuario')
            ->first();
            if ($license->estado == 'I') {
                return response()->json([
                    'status' => '400',
                    'errors' => '{"email":["Su cuenta esta inactiva."]}'
                ], 400);
            }
        }

        $type = auth()->user()->tipo;

        $exist = LicenseDistribution::where([
            'id_usuario' => auth()->user()->id_usuario,
            'id_plan' => '1'
        ])->first();

        return response()->json([
            'token' => $token,
            'id' => auth()->user()->id_usuario,
            'email' => auth()->user()->email,
            'firstName' => auth()->user()->nombres,
            'lastName' => auth()->user()->apellidos,
            'number' => auth()->user()->movil,
            'address' => auth()->user()->direccion,
            'country' => auth()->user()->pais,
            'city' => auth()->user()->ciudad,
            'avatar' => auth()->user()->foto,
            'code' => auth()->user()->ubi_codigo,
            'role' => $type == 'A' ? 'Admin' : ($type == 'U' ? 'Analyst' : ($type == 'P' && empty($exist) ? 'Owner' : 'Free')),
        ]);
    }

    public function loginSocial(Request $request) {

        $user = User::where('email', $request->email)->first();

        if ($user) {

            $token = auth()->login($user);

            return response()->json([
                'token' => $token,
                'id' => auth()->user()->id_usuario,
                'email' => auth()->user()->email,
                'firstName' => auth()->user()->nombres,
                'lastName' => auth()->user()->apellidos,
                'avatar' => 'avatar-s-11.jpg',
                'role' => 'Admin',
                'action' => '1'
            ]);
        } else {

            // return $this->register($request);
            return response()->json([
                'action' => '2'
            ]);

        }

    }

    public function loginLn(Request $request) {

        $param = 'grant_type=authorization_code&code='.$request->code.'&client_id=78eygsqp7carij&client_secret=zoT6SZTxFHhqLguP&redirect_uri=https://frontend-indicadores.devaztweb.com/admin/response';

        $response = Http::get('https://www.linkedin.com/oauth/v2/accessToken?'. $param);
        $quizzes = json_decode($response->body());


        $post = Http::withHeaders([
            'Authorization' => 'Bearer ' . $quizzes->access_token,
            ])->get('https://api.linkedin.com/v2/me');

        $quizzesPost = json_decode($post->body());

        $email = Http::withHeaders([
            'Authorization' => 'Bearer ' . $quizzes->access_token,
            ])->get('https://api.linkedin.com/v2/clientAwareMemberHandles?q=members&projection=(elements*(true,EMAIL,handle~,emailAddress))');

        $quizzesEmail = json_decode($email->body());

        return response()->json([
            'response' => $quizzesPost,
            'email' => $quizzesEmail
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tbl_usuarios',
            'password' => 'required|string|min:6|confirmed',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => '400',
                'errors' => $validator->errors()->toJson()
            ], 400);
        }

        $new = Carbon::parse($request->get('date'));


        $businessIncrementing = $this->incrementing();
        $usuario = $this->incrementingUser();
        $history = $this->incrementingHistory();
        $tipo_licencia = $request->get('token') != '' ? '2' : '0';
        $plan = Plan::where('tipo_licencia', $tipo_licencia)->first();

        $user = User::create([
            'id_usuario' => $usuario ? $usuario->id_usuario + 1 : '1',
            'email' => $request->get('email'),
            'nombres' => $request->get('firstName'),
            'apellidos' => $request->get('lastName'),
            'movil' => $request->get('phone'),
            'password' => Hash::make($request->get('password')),
            'tipo' => 'P',
            'usuario' => $this->formatUser($request),
            'foto' => '/app/avatar.png',
            'estado' => 'A',
            'fecha_registro' => $request->get('date')
        ]);

        $name_business = $request->get('business');
        $ruc = $request->get('ruc');

        if(!$request->get('business')) {
            $name_business = $user->nombres. ' '.  $user->apellidos;
            $ruc = 'RUC-001';
        }

        if ($request->get('businessExist') == 'N') {
            $name_business = $user->nombres. ' '.  $user->apellidos;
            $ruc = 'RUC-001';
        }

        $business = Business::create([
            'id_empresa' => $businessIncrementing ? $businessIncrementing->id_empresa + 1 : '1',
            'id_usuario' => $user->id_usuario,
            'nombre_empresa' => ''.$name_business.'',
            'ruc' => ''.$ruc.'',
            'estado' => 'A',
            'tipo_empresa' => '1'
        ]);





        $userPlan = UserPlan::create([
            'id_usuario' => $user->id_usuario,
            'id_plan' => $plan->id_plan,
            'estado' => 'A'
        ]);


        $date = '';
        $endDate = '';
        $id_periodo_plan = '1';

        if($request->get('token') != '') {
            $id_donacion = Crypt::decrypt($request->get('token'));

            $donate = Donate::find($id_donacion);
            $donate->id_usuario_invitado = $user->id_usuario;
            $donate->email = $request->get('email');
            $donate->estado = '3';

            $id_periodo_plan = $donate->id_periodo_plan;
            $periodo_plan = PlanPeriod::find($donate->id_periodo_plan);

            $date = Carbon::parse($request->get('date'));
            $endDate = $new->addMonth($periodo_plan->numero);


            $donate->save();
        }


        /*$his = HistoryPlans::create([
            'id_periodo_plan' => '1',
            'id_usuario' => $user->id_usuario,
            'id_historial' => $history ? $history->id_historial + 1 : '1',
            'fecha_inicio' => $date,
            'fecha_fin' => $endDate,
            'numero' => $plan->numero,
            'estado' => 'A'
        ]);
*/
        $license = LicenseDistribution::create([
            'id_usuario' => $user->id_usuario,
            'id_empresa' => $business->id_empresa,
            'id_usuario_asignado' => $user->id_usuario,
            'id_historial' => '0',
            'fecha_inicio' => $date,
            'fecha_fin' => $endDate,
            'empresa_defecto' => 'S',
            'estado' => 'A',
            'id_plan' => $plan->id_plan,
        ]);





        $token = auth()->login($user);

       return response()->json([
            'token' => $token,
            'access_token' => $token,
            'token_type' => 'bearer',
            'id' => auth()->user()->id,
            'email' => auth()->user()->email,
            'firstName' => auth()->user()->nombres,
            'lastName' => auth()->user()->apellidos,
            'avatar' => 'avatar-s-11.jpg',
            'role' => 'Admin',
        ]);
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            /*'first_name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|integer|max:255',
            'gender' => 'required|string|max:1',
            'address'=> 'required|string|max:255',
            'ubi_cod'=> 'required|string|max:11',
            'date_birth'=> 'required|date',
            'date_reg'=> 'required|date',
            'type' => 'required|string|max:1',
            'status' => 'required|string|max:1',
            'pass' => 'required|string|min:6|confirmed',
            'id_empresa' => 'required|string|max:255'*/
            'firstName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::find($request->get('id_usuario'));
        $user->email = $request->get('email');
        $user->nombres = $request->get('firstName');
        $user->apellidos = $request->get('lastName');
        $user->movil = $request->get('phone');
        //$user->password = Hash::make($request->get('password'));
        $user->usuario = $this->formatUser($request);
        //$user->ubi_codigo = $request->get('dev') . $request->get('prov'). $request->get('distrito');
        //$user->estado = $request->get('estado');
        $user->direccion = $request->get('direccion');
        $user->fecha_nacimiento = $request->get('fecha_nacimiento');
        $user->sexo = $request->get('sexo');
        $user->pais = $request->get('pais');
        $user->ciudad = $request->get('ciudad');
        $user->ubi_codigo = $request->get('ubi_codigo');
        $user->save();

        return response()->json([
            'status' => '200',
            'message' => 'Updated record correctly',
        ], 200);

    }

    private function incrementingHistory()
    {
        return HistoryPlans::orderBy('id_historial', 'desc')->first();
    }

    private function incrementingUser()
    {
        return User::orderBy('id_usuario', 'desc')->first();
    }

    private function incrementing()
    {
        return Business::orderBy('id_empresa', 'desc')->first();
    }

    public function formatUser(Request $request)
    {
        $Name = $request->get('firstName');
        $Ape = $request->get('lastName');

        $fNom = explode(" ", $Name);
        $fApe = explode(" ", $Ape);

        return (count($fNom) > 0 ? $fNom[0] : $Name) . (count($fApe) > 0 ? $fApe[0] : $Ape);

    }

    public function uploadImage(Request $request)
    {
        //$file = $this->upload($request);

        $target_dir = "app/avatars/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $uploadOk = '';
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image

        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                //echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
              } else {
              }
        } else {
            $uploadOk = 0;
        }

        $user = User::find(auth()->user()->id_usuario);
        $user->foto = '/' . $target_file;
        $user->save();

        return response()->json([
            'message' => 'image saved successfully',
            'avatar' => '/' . $target_file,
        ], 200)->getContent();


    }

    private function upload(Request $request)
    {
        if(!$request->hasFile('file') && !$request->file('file')->isValid()) {
            return 'Error';
        }



        /*try {
            $file = $request->file('file')->getClientOriginalName();
            Storage::disk('local')->put('avatars/' . $file, file_get_contents($request->file('file')));
            return $request->file('file');
        } catch (\Throwable $th) {
            return response()->json($th, 200)->getContent();
        }*/

    }

    public function listUser()
    {

        $user = User::select('tbl_usuarios.id_usuario as id', 'email', 'nombres as name', 'apellidos as lastName',
            'tbl_usuarios.estado as status', 'foto as avatar', 'usuario as user', 'nombre_empresa as business')
            ->join('tbl_distribucion_licencias', 'tbl_distribucion_licencias.id_usuario', '=', 'tbl_usuarios.id_usuario')
            ->join('tbl_empresas', 'tbl_empresas.id_empresa', '=', 'tbl_distribucion_licencias.id_empresa')
            ->where('tbl_usuarios.tipo', 'P')
            ->orderBy('tbl_usuarios.id_usuario', 'desc')
            ->groupBy("tbl_usuarios.id_usuario")
            ->get();

        return response()->json([
            'status' => '200',
            'user' => $user,
        ]);

    }


    public function listUserU(Request $request)
    {

        $user = User::select('tbl_usuarios.id_usuario as id', 'email', 'nombres as name', 'apellidos as lastName',
            'tbl_distribucion_licencias.estado as status', 'foto as avatar', 'descripcion as description', 'usuario as user',
            'nombre_empresa as business', 'tipo_empresa as type')
            ->selectRaw('(select count(*) from tbl_distribucion_licencias where tbl_distribucion_licencias.id_usuario = tbl_usuarios.id_usuario) as countLicense')
            ->join('tbl_distribucion_licencias', 'tbl_distribucion_licencias.id_usuario_asignado', '=', 'tbl_usuarios.id_usuario')
            ->join('tbl_empresas', 'tbl_empresas.id_empresa', '=', 'tbl_distribucion_licencias.id_empresa')
            ->join('tbl_planes', 'tbl_planes.id_plan', '=', 'tbl_distribucion_licencias.id_plan')
            ->where('tbl_distribucion_licencias.id_usuario', $request->get('id'))
            ->orderBy('tbl_usuarios.id_usuario', 'desc')
            ->get();

        $userCount = User::join('tbl_distribucion_licencias', 'tbl_distribucion_licencias.id_usuario', '=', 'tbl_usuarios.id_usuario')
            ->where('tbl_distribucion_licencias.id_usuario', $request->get('id'))
            ->get();

        $userBusiness = Business::join('tbl_distribucion_licencias', 'tbl_distribucion_licencias.id_empresa', '=', 'tbl_empresas.id_empresa')
            ->where('tbl_distribucion_licencias.id_usuario', $request->get('id'))
            ->orderBy('tbl_distribucion_licencias.id_usuario', 'desc')
            ->groupBy("tbl_distribucion_licencias.id_usuario")
            ->get();

        $history = HistoryPlans::select('fecha_inicio as start', 'fecha_fin as end', 'numero as cant', 'id_plan as plan',
            'estado as status', 'id_historial as id', 'tbl_pedidos.estado_pago as order')
            ->join('tbl_pedidos', 'tbl_pedidos.id_pedido', '=', 'tbl_historial_planes.id_pedido')
            ->where('tbl_historial_planes.id_usuario', $request->get('id'))
            ->orderBy('id_historial', 'desc')
            ->get();

        return response()->json([
            'status' => '200',
            'user' => $user,
            'userCount' =>  $userCount->count(),
            'userBusiness' =>  $userBusiness->count(),
            'history' => $history
        ]);

    }

    public function activateUser(Request $request)
    {

        $user = User::find($request->get('id'));
        $user->estado = $request->get('status');
        $user->save();

        return response()->json([
            'message' => 'Estado de usuario actualizado correctamente'
        ]);
    }

    public function updatePassword(Request $request) {


        if(auth()->user()->email != $request->get('email')) {
            return response()->json([
                'status' => '400',
                'message' => 'Usuario no Correcto'
            ], 400);
        }


        $credentials = request(['email', 'password']);
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'status' => '400',
                'errors' => '{"email":["Contraseña es incorrecto."]}'
            ], 400);

        }
        $user = User::where('email', $request->get('email'))->first();

        $user->password = Hash::make($request->get('confirmPassword'));
        $user->save();

        return response()->json([
            'status' => '200',
            'message' => 'Contraseña actualizado correctamente'
        ], 200);
    }

    public function sendPassword(Request $request) {

        $existUSer = !!User::where('email', $request->get('email'))->first();

        if (!$existUSer) {
            return response()->json([
                'error' => 'Email no existe!',
            ], 400);
        }

        $token = Str::random(60);

        $oldToken = PasswordReset::where('email', $request->get('email'))->first();
        Mail::to($request->get('email'))->send(new ResetPasswordMail($token));
        if($oldToken) {
            $oldToken->token = $token;
            $oldToken->save();
            return response()->json([
                'user' => $oldToken,
            ], 200);
        }

        PasswordReset::create([
            'email' => $request->get('email'),
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => 'Correo enviado correctamente',
        ], 200);

    }

    public function changePassword(Request $request) {

        $oldToken = PasswordReset::where('token', $request->get('token'))->first();
        $user = User::where('email', $oldToken->email)->first();
        $user->password = Hash::make($request->get('password'));
        $user->save();

        return response()->json([
            'status' => '200',
            'message' => 'Contraseña actualizado correctamente',
        ], 200);

    }

    public function updateHistory(Request $request) {


        $historial = HistoryPlans::where('id_historial', $request->get('id'))->first();

        Order::where([
            'id_pedido' => $historial->id_pedido,
        ])
        ->update([
            'estado_pago' => $request->get('order'),
        ]);

        return response()->json([
            'status' => '200',
            'message' => 'Estado del grupo actualizado',
        ], 200);

    }

}
