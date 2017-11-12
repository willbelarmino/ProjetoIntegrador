<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Exception;
use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Http\Model\Usuario;

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
    protected $redirectTo = 'home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('sair');
    }

    public function index(){
        return view('auth/login');
    }

    protected function doLogin(Request $request)
    {
        try {
            $param = $request->all();


            $login_exist = Usuario::from('usuario AS u')
                ->where([
                    ['email', '=', $param['email']],
                    ['senha', '=', md5($param['senha'])]
                ])->first();

            if (empty($login_exist)) {
                throw new CustomException('E-mail ou senha inválidos!');
            } else {
                Auth::login($login_exist);
                $request->session()->put('usuarioLogado', $login_exist);
                $request->session()->put('periodoSelecionadoInicio', date('Ym01'));
                $request->session()->put('periodoSelecionadoFim', date('Ymt'));

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
                'message' =>  $e->getMessage()//'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function sair(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
