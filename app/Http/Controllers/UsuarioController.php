<?php

namespace App\Http\Controllers;


use App\Http\Facade\UsuarioFacade;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;
use PDF;
use App;
use Auth;


class UsuarioController extends Controller
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
            
            return view('usuario/minhaconta',[                
                'page'=>'Minha Conta',  
                'menuView'=>'minhaconta',             
                'usuario'=>$usuarioLogado,
                'nomeMes'=>$periodo->mes,
                'resize'=>$periodo->resize
            ]);

        } catch (Exception $e) {
            return view ('error');
        }
        
    }
    

    protected function delete(Request $request){
        try {
            $param = $request->all();
            
            try {

                CategoriaFacade::deletarCategoria($param['id']);

                return response()->json([
                    'status' => 'success',
                    'message' =>  'Categoria removida com sucesso.'
                 ]);

            } catch (CustomException $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' =>  'Ops. Erro ao remover categoria. Tente novamente mais tarde.'
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

            try {

                UsuarioFacade::alterarDados($param['id'], null, $param['nome']);

                return response()->json([
                    'status' => 'success',
                    'message' =>  'Dados alterados com sucesso.'
                 ]);

            } catch (CustomException $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' =>  'Ops. Erro ao alterar os dados. Tente novamente mais tarde.'
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