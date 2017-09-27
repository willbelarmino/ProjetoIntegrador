<?php

namespace App\Http\Controllers\Auth;

use App\Http\Model\Usuario;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Exception;
use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(){
        return view('login');
    }

    protected function doLogin(Request $request)
    {
        try {
            $param = $request->all();
            $login_exist = DB::table('usuario')->where([
                ['email', '=', $param['email']],
                ['senha', '=', md5($param['senha'])]
            ])->first();
            if (empty($login_exist)) {
                throw new CustomException('E-mail ou senha inválidos!');
            } else {

                $request->session()->put('usuarioLogado', $login_exist);
                $request->session()->put('periodoSelecionado', date('Ymd'));

                return response()->json([
                    'status' => 'success',
                    'message' =>  'Usuário Autenticado!'
                ]);
            }
        }catch (CustomException $ex) {
            return response()->json([
                'status' => 'error',
                'message' =>  $ex->getMessage()
            ]);
        }catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' =>  'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }
}
