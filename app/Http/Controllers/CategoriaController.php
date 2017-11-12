<?php

namespace App\Http\Controllers;


use App\Http\Facade\CategoriaFacade;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;
use PDF;
use App;
use Auth;


class CategoriaController extends Controller
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

            $categorias = CategoriaFacade::getCategorias($usuarioLogado);
            
            return view('categorias/categorias',[
                'menuView'=>'categorias',
                'page'=>'Categorias',
                'categorias'=>$categorias,
                'usuario'=>$usuarioLogado,
                'nomeMes'=>$periodo->mes,
                'resize'=>$periodo->resize
            ]);

        } catch (Exception $e) {
            return view ('error');
        }
        
    }

    protected function create(Request $request){
        try {

            $usuarioLogado = self::getUsuario();
            $param = $request->all();

            try {
                $nome =  $param['nome'];

                if ($param['hasLimite']=="false" || $param['limite']=="R$ 0,00") {
                    CategoriaFacade::criarCategoriaSemLimite($nome, $usuarioLogado);
                } else {
                    $limite = $param['limite'];
                    CategoriaFacade::criarCategoriaComLimite($nome, $limite, $usuarioLogado);
                }
                return response()->json([
                    'status' => 'success',
                    'message' =>  'Categoria cadastrada com sucesso.'
                ]);

            } catch (CustomException $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' =>  $ex->getMessage()
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() //'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
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
                if ($param['hasLimite']=="false" || $param['limite']=="R$ 0,00") {
                    CategoriaFacade::editarCategoria($param['id'], null, $param['nome']);
                } else {
                    CategoriaFacade::editarCategoria($param['id'], $param['limite'], $param['nome']);
                }

                return response()->json([
                    'status' => 'success',
                    'message' =>  'Categoria alterada com sucesso.'
                 ]);

            } catch (CustomException $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' =>  'Ops. Erro ao alterar categoria. Tente novamente mais tarde.'
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
