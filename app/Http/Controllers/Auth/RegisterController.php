<?php

namespace App\Http\Controllers\Auth;




use App\Http\Model\Usuario;
use App\Http\Controllers\Controller;
use Exception;
use App\Exceptions\CustomException;
use App\Exceptions\FormException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;



class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'bail|required|max:3',
            'nome' => 'required',
            'senha' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request  $request
     * @return \App\User
     */
    protected function create(Request $request)
    {
        try {
            $param = $request->all();
            $login_exist = DB::table('usuario')->where('email', $param['email'])->first();
            if (empty($login_exist)) {

                $new_user = Usuario::create([
                    'nome' => $param['nome'],
                    'email' => $param['email'],
                    'bloquear_limite_categoria' => false,
                    'unificar_indicadores_convidado' => false,
                    'senha' => md5($param['senha'])
                ]);

                if (empty($new_user)) {
                    throw new CustomException('Erro ao cadastrar usuário. Tente novamente mais tarde.');
                }
                $file = $request->file('avatar');
                if (!empty($file)) {
                    $avatar = Image::make($file)->resize(128, 128)->encode('jpg')->stream();
                    $file_avatar_name = $new_user->id.time().'.jpg';
                    Storage::disk('local-avatar')->put($file_avatar_name,$avatar->__toString());
                    DB::table('usuario')->where('id', $new_user->id)->update(['image' => $file_avatar_name]);
                }
                $new_user =  DB::table('usuario')->where([
                    ['id', '=', $new_user->id]
                ])->first();
                $request->session()->put('usuarioLogado', $new_user);
                $request->session()->put('periodoSelecionadoInicio', date('Ym01'));
                $request->session()->put('periodoSelecionadoFim', date('Ymt'));

                return response()->json([
                    'status' => 'success',
                    'message' =>  'categorias'
                ]);
            } else {
                throw new CustomException('Login já cadastrado no sistema');
            }
        }catch (CustomException $ex) {
            return response()->json([
                'status' => 'error',
                'message' =>  $ex->getMessage()
            ]);
        }catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function index(){
        return view('registro');
    }
}
