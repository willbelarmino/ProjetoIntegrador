<?php


namespace App\Http\Controllers;

use App\Http\Facade\ContaFacade;
use App\Http\Facade\CartaoFacade;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;



class CartaoController extends Controller
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
            
            if ($periodo == null || $usuarioLogado == null) {
                throw new Exception();
            }

            $cartoes = CartaoFacade::getCartoes($usuarioLogado);

            $contas = ContaFacade::getContas($usuarioLogado);
         
            return view('cartoes/cartoes',[
                'menuView'=>'cartoes',
                'page'=>'Cartões',
                'cartoes'=>$cartoes,
                'contas'=>$contas,
                'usuario'=>$usuarioLogado,
                'nomeMes'=>$periodo->mes,
                'resize'=>$periodo->resize
            ]);

        } catch (Exception $ex) {
            return view ('cartoes/error');
        }
    }

    protected function create(Request $request){
        try {
            $usuarioLogado = self::getUsuario();
            $param = $request->all();          

            CartaoFacade::criarCartao(
                $param['limite'], 
                $param['vencimento'], 
                $param['fechamento'], 
                $param['independente'], 
                $param['nome'], 
                $param['conta'], 
                $usuarioLogado
            );

            return response()->json([
                'status' => 'success',
                'message' =>  'Cartão cadastrado com sucesso.',
            ]);           
       
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

            CartaoFacade::deletarCartao($param['id'], $param['independente'], $param['conta']);

            return response()->json([
                'status' => 'success',
                'message' =>  'Cartão removido com sucesso.'
            ]);          

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() //'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function edit(Request $request){
        try {
            $usuarioLogado = self::getUsuario();
            $param = $request->all();           

            CartaoFacade::editarCartao($param['id'], $param['limite'], $param['vencimento'], $param['fechamento']);

            return response()->json([
                'status' => 'success',
                'message' =>  'Cartão alterado com sucesso.',
            ]);           
        
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() //'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

}