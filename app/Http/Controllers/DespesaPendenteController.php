<?php

namespace App\Http\Controllers;

use App\Http\Facade\DespesaFacade;
use App\Http\Facade\CategoriaFacade;
use App\Http\Facade\ContaFacade;
use App\Http\Facade\CartaoFacade;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;
use PDF;
use App;
use Session;

class DespesaPendenteController extends Controller
{
    public function getUsuario() {
        try {
            return UtilsController::getUsuarioLogado();
        } catch (Exception $e) {
            return null;
        }
    }

    public function getPeriodo() {
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

            $usuarioLogado = $request->session()->get('usuarioLogado');
            $param = $request->all();
            if (!empty($param['valor']) && $param['valor']!="R$ 0,00") {
                $valor = str_replace("R$", "", $param['valor']);
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor);

                $dia = substr($param['vencimento'], 0, -8);
                $mes = substr($param['vencimento'], 3, -5);
                $ano = substr($param['vencimento'], -4);
                $vencimento = $ano.$mes.$dia;

                if (!empty($param['credito'])) {
                    DB::select("CALL criarDespesaPendente(
                    '".$param['nome']."', 
                    ".$valor.", 
                    ".$param['parcela']." ,
                    '".$vencimento."',
                    ".$param['categoria'].",
                    ".$param['credito'].")");
                } else {
                    DB::select("CALL criarDespesaPendente(
                    '".$param['nome']."', 
                    ".$valor.", 
                    ".$param['parcela']." ,
                    '".$vencimento."',  
                    ".$param['categoria'].",
                    null)");

                }

            } else {
                throw new CustomException('Ops. O valor não pode ser R$ 0,00.');
            }

            return response()->json([
                'status' => 'success',
                'message' =>  'Despesa cadastrada com sucesso.'
            ]);

        }catch (CustomException $ex) {
            return response()->json([
                'status' => 'error',
                'message' =>  $ex->getMessage()
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
            $periodoSelecionadoInicio = $request->session()->get('periodoSelecionadoInicio');
            $periodoSelecionadoFim = $request->session()->get('periodoSelecionadoFim');
            $param = $request->all();
            DB::select("CALL excluirDespesaPendente(                    
                    ".$param['id'].",
                    '".$periodoSelecionadoInicio."',
                    '".$periodoSelecionadoFim."')");
            return response()->json([
                'status' => 'success',
                'message' =>  'Despesa removida com sucesso.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ops. Erro ao remover registro. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function edit(Request $request){
        try {
            $param = $request->all();

            $valor = str_replace("R$", "", $param['valor']);
            $valor = str_replace(".", "", $valor);
            $valor = str_replace(",", ".", $valor);

            $dia = substr($param['vencimento'], 0, -8);
            $mes = substr($param['vencimento'], 3, -5);
            $ano = substr($param['vencimento'], -4);
            $vencimento = $ano.$mes.$dia;

            if (!empty($param['credito'])) {
                DB::select("CALL alterarDespesaPendente(                    
                    ".$param['id'].",
                    '".$param['nome']."',
                    ".$valor.",
                    '".$vencimento."',
                    ".$param['categoria'].",
                    ".$param['credito'].")");
            } else {
                DB::select("CALL alterarDespesaPendente(                    
                    ".$param['id'].",
                    '".$param['nome']."',
                    ".$valor.",
                    '".$vencimento."',
                    ".$param['categoria'].",
                    null)");
            }

            return response()->json([
                'status' => 'success',
                'message' =>  'Despesa alterada com sucesso.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() //'Ops. Erro ao alterar registro. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function toPDF(Request $request){
        $parcelasPendentes = self::getParcelasPendentes($request);
        $pdf = PDF::loadView('despesas/relatorios/pendente-rel', ['link'=>$parcelasPendentes, 'title'=>'Despesas Pendentes']);
        return $pdf->stream();
    }


    protected function pagar(Request $request){

        try {
            $param = $request->all();
            DB::select("CALL pagarDespesa(                    
                        " . $param['id'] . ",
                        " . $param['conta'] . "
                        )");
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
