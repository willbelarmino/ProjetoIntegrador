<?php

namespace App\Http\Controllers;

use App\Http\Facade\DespesaFacade;
use App\Http\Facade\CategoriaFacade;
use App\Http\Facade\ContaFacade;
use App\Http\Facade\CartaoFacade;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;
use PDF;
use App;


class DespesaPagaController extends Controller
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

            $categorias = CategoriaFacade::getCategorias($usuarioLogado);

            $contas = ContaFacade::getContas($usuarioLogado, $periodo);

            $cartoes = CartaoFacade::getCartoes($usuarioLogado);

            $parcelasPagas = DespesaFacade::getParcelasPagas($usuarioLogado, $periodo);

            return view('despesas/paga',
                ['menuView'=>'pagas',
                    'page'=>'Despesas Pagas',
                    'parcelas'=>$parcelasPagas,
                    'categorias'=>$categorias,
                    'contas'=>$contas,
                    'cartoes'=>$cartoes,
                    'nomeMes'=>$periodo->mes,
                    'resize'=>$periodo->resize,
                    'usuario'=>$usuarioLogado
            ]);

        } catch (Exception $ex) {
            return view ('despesas/error');
        }

    }    

    protected function create(Request $request){
        try {

            $usuarioLogado = self::getUsuario();
            $param = $request->all();

            try {

                DespesaFacade::criarDespesaPaga($param['nome'], $param['valor'], $param['pagamento'], $param['parcela'], $param['categoria'], $param['credito'], $param['conta']);

                return response()->json([
                    'status' => 'success',
                    'message' =>  'Despesa cadastrada com sucesso.'
                ]);

            } catch (CustomException $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' =>  'Ops. Erro ao cadastrar despesa. Tente novamente mais tarde.'
                ]);
            }
        
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' =>  'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function delete(Request $request){
        try {

            $periodo = self::getPeriodo();
            $param = $request->all();
            
            try {

                 DespesaFacade::deletarDespesaPaga($param['id'], $periodo);  

                return response()->json([
                    'status' => 'success',
                    'message' =>  'Despesa removida com sucesso.'
                ]);

            } catch (CustomException $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ops. Erro ao remover despesa. Tente novamente mais tarde.'
                ]);  
            }            

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ops. ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function edit(Request $request){
        
    }
    

    protected function toPDF(Request $request){
        try {

            $usuarioLogado = self::getUsuario();
            $periodo = self::getPeriodo();
            $parcelasPagas = DespesaFacade::getParcelasPagas($usuarioLogado, $periodo);
            $pdf = PDF::loadView('despesas/relatorios/paga-rel', ['link'=>$parcelasPagas, 'title'=>'Despesas Pagas']);
            return $pdf->stream();

        } catch (Exception $e) {
            
        }
    }

}
