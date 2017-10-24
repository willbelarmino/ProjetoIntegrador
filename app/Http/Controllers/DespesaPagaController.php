<?php

namespace App\Http\Controllers;

use App\Http\Model\CartaoCredito;
use Carbon\Carbon;
use DateTime;
use App\Http\Model\Despesa;
use App\Http\Model\Usuario;
use App\Http\Model\ParcelaPendente;
use App\Http\Model\ParcelaPaga;
use App\Http\Model\Categoria;
use App\Http\Model\Conta;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;
use PDF;
use App;
use Barryvdh\Snappy;

class DespesaPagaController extends Controller
{

    public function index(Request $request){
        $usuarioLogado = $request->session()->get('usuarioLogado');
        $periodo = UtilsController::getPeriodo($request);
        $periodo = $periodo->getData();

        $contas = Categoria::from('conta AS c')
            ->where("c.id_usuario",$usuarioLogado->id)
            ->get();

        $categorias = Categoria::from('categoria AS c')
            ->where("c.id_usuario",$usuarioLogado->id)
            ->get();    

        $cartoes = CartaoCredito::from('cartao_credito AS cc')
            ->join('conta AS c','cc.id_conta','=','c.id')
            ->where("c.id_usuario",$usuarioLogado->id)
            ->select('cc.*')
            ->get();

        $parcelasPagas = ParcelaPaga::with(['parcelaPendente.despesa.categoria' => function ($query) use ($usuarioLogado) {
            $query->where('categoria.id_usuario', '=', $usuarioLogado->id);
        }])->whereBetween('dt_pagamento', [
            $periodo->periodoSelecionadoInicio,
            $periodo->periodoSelecionadoFim
        ])->get();
        

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
    }

    protected function create(Request $request){
        try {

            $usuarioLogado = $request->session()->get('usuarioLogado');
            $param = $request->all();
            if (!empty($param['valor']) && $param['valor']!="R$ 0,00") {
                $valor = str_replace("R$", "", $param['valor']);
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor);

                $dia = substr($param['pagamento'], 0, -8);
                $mes = substr($param['pagamento'], 3, -5);
                $ano = substr($param['pagamento'], -4);
                $pagamento = $ano.$mes.$dia;

                if (!empty($param['credito'])) {
                    DB::select("CALL criarDespesaPaga(
                    '".$param['nome']."', 
                    ".$valor.", 
                    ".$param['parcela']." ,
                    '".$pagamento."',
                    ".$param['categoria'].",
                    ".$param['conta'].",
                    ".$param['credito'].")");
                } else {
                    DB::select("CALL criarDespesaPaga(
                    '".$param['nome']."', 
                    ".$valor.", 
                    ".$param['parcela']." ,
                    '".$pagamento."',  
                    ".$param['categoria'].",
                    ".$param['conta'].",
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
        $usuarioLogado = $request->session()->get('usuarioLogado');
        $periodoSelecionadoInicio = $request->session()->get('periodoSelecionadoInicio');
        $periodoSelecionadoFim = $request->session()->get('periodoSelecionadoFim');
        
        $parcelasPagas = ParcelaPaga::with(['parcelaPendente.despesa.categoria' => function ($query) use ($usuarioLogado) {
            $query->where('categoria.id_usuario', '=', $usuarioLogado->id);
        }])->whereBetween('dt_pagamento', [
            $periodoSelecionadoInicio,
            $periodoSelecionadoFim
        ])->get();

        $pdf = PDF::loadView('despesas/relatorios/paga-rel', ['link'=>$parcelasPagas, 'title'=>'Despesas Pagas']);
        return $pdf->stream();
    }

}
