<?php


namespace App\Http\Controllers;


use App\Http\Facade\ContaFacade;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;



class ContaController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function getUsuario() {
        try {
            return UtilsController::getUsuarioLogado();
        } catch (Exception $e) {
            return null;
        }
    }

    protected function getPeriodo() {
        try {
            $periodo = UtilsController::getPeriodo();
            $periodo = $periodo->getData();
            return $periodo;
        } catch (Exception $ex) {
            return null;
        }
    }

    public function index(Request $request){
        try {

            $usuarioLogado = self::getUsuario();
            $periodo = self::getPeriodo();

            $contas = ContaFacade::getContas($usuarioLogado);
            
            return view('contas/contas',[
                'menuView'=>'contas',
                'page'=>'Contas',
                'contas'=>$contas,
                'usuario'=>$usuarioLogado,
                'nomeMes'=>$periodo->mes,
                'resize'=>$periodo->resize
            ]);

        } catch (Exception $e) {
            return view ('contas/error');
        }
        
    }
    

    protected function create(Request $request){
        try {

            $usuarioLogado = $request->session()->get('usuarioLogado');
            $param = $request->all();
            $file = $request->file('image');

            try {
                
                ContaFacade::criarConta($param['nome'], $param['tipo'], $param['indicador'], $file, $usuarioLogado);     

                return response()->json([
                    'status' => 'success',
                    'message' =>  'Conta cadastrada com sucesso.',
                ]);

            } catch (CustomException $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' =>  'Ops. Erro ao cadastrar conta. Tente novamente mais tarde.'
                ]);
            }  
       
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function delete(Request $request){
        try {

            $param = $request->all();

            try {
                
                ContaFacade::deletarConta($param['id']);     

                return response()->json([
                    'status' => 'success',
                    'message' =>  'Conta removida com sucesso.',
                ]);

            } catch (CustomException $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' =>  'Ops. Erro ao remover conta. Tente novamente mais tarde.'
                ]);
            }  
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function edit(Request $request){
        try {
            $param = $request->all();
            $file = $request->file('conta-view');

            try {
                
                ContaFacade::editarConta($param['id'], $param['nome'], $param['indicador'], $param['imagem'], $param['tipo'], $file);     

                return response()->json([
                    'status' => 'success',
                    'message' =>  'Conta alterada com sucesso.',
                ]);

            } catch (CustomException $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' =>  'Ops. Erro ao alterar conta. Tente novamente mais tarde.'
                ]);
            }  
        
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' =>  'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }

    }

}