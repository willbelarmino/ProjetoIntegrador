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
use Session;

class DespesaPendenteController extends Controller
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

            $contas = ContaFacade::getContas($usuarioLogado);

            $cartoes = CartaoFacade::getCartoes($usuarioLogado);

            $parcelasPendentes = DespesaFacade::getParcelasPendentes($usuarioLogado, $periodo);

            return view('despesas/pendente',
                [
                    'menuView' => 'pendentes',
                    'page' => 'Despesas Pendentes',
                    'parcelas' => $parcelasPendentes,
                    'categorias' => $categorias,
                    'cartoes' => $cartoes,
                    'contas' => $contas,
                    'nomeMes' => $periodo->mes,
                    'resize' => $periodo->resize,
                    'usuario' => $usuarioLogado
                ]);
        } catch (Exception $ex) {
            return view ('despesas/error');
        }

    }

    protected function create(Request $request){
        try {

            $usuarioLogado = self::getUsuario();
            $param = $request->all(); 

            if ($param['isCredito']=='false') {
                
                DespesaFacade::criarDespesaPendenteSemCredito(
                    $param['nome'], 
                    $param['valor'], 
                    $param['vencimento'], 
                    $param['parcela'], 
                    $param['categoria']
                );
               
            } else {
                
                DespesaFacade::criarDespesaPendenteComCredito(
                    $param['nome'], 
                    $param['valor'], 
                    $param['vencimento'], 
                    $param['parcela'], 
                    $param['categoria'], 
                    $param['credito']
                );
               
            }           

            return response()->json([
                'status' => 'success',
                'message' =>  'Despesa cadastrada com sucesso.'
            ]);           
        
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' =>  $e->getMessage() //'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function delete(Request $request){
        try {

            $periodo = self::getPeriodo();
            $param = $request->all();            
           
            DespesaFacade::deletarDespesaPendente($param['id'], $periodo);  

            return response()->json([
                'status' => 'success',
                'message' =>  'Despesa removida com sucesso.'
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

            $param = $request->all();           

            DespesaFacade::editarDespesaPendente($param['id'], $param['nome'], $param['valor'], $param['vencimento'], $param['categoria'], $param['credito']); 
               
            return response()->json([
                'status' => 'success',
                'message' =>  'Despesa alterada com sucesso.'
            ]);
           
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() //'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function toPDF(Request $request){
        try {

            $usuarioLogado = self::getUsuario();
            $periodo = self::getPeriodo();
            $parcelasPendentes = DespesaFacade::getParcelasPendentes($usuarioLogado, $periodo);
            $pdf = PDF::loadView('despesas/relatorios/pendente-rel', ['link'=>$parcelasPendentes, 'title'=>'Despesas Pendentes']);
            return $pdf->stream();

        } catch (Exception $e) {
            
        }
    }


    protected function pagar(Request $request){

        try {
            $param = $request->all();          
          
            DespesaFacade::pagarDespesa($param['id'], $param['conta']);
               
            return response()->json([
                'status' => 'success',
                'message' =>  'Despesa paga com sucesso.',
            ]);           

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' =>  $e->getMessage() //'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

}
