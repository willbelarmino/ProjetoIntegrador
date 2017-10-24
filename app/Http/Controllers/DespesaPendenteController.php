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
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;
use PDF;
use App;
use Barryvdh\Snappy;

class DespesaPendenteController extends Controller
{

    public function index(Request $request){
        $usuarioLogado = $request->session()->get('usuarioLogado');
        $periodo = UtilsController::getPeriodo($request);
        $periodo = $periodo->getData();
        $categorias = Categoria::from('categoria AS c')
            ->where("c.id_usuario",$usuarioLogado->id)
            ->get();

        $contas = DB::table('conta')->where('id_usuario', $usuarioLogado->id)->get();

        $cartoes = CartaoCredito::from('cartao_credito AS cc')
            ->join('conta AS c','cc.id_conta','=','c.id')
            ->where("c.id_usuario",$usuarioLogado->id)
            ->select('cc.*')
            ->get();


        $parcelasPendentes = ParcelaPendente::with(['despesa.categoria' => function ($query) use ($usuarioLogado) {
            $query->where('categoria.id_usuario', '=', $usuarioLogado->id);
        }])->whereBetween('dt_vencimento', [
            $periodo->periodoSelecionadoInicio,
            $periodo->periodoSelecionadoFim
        ])->get();




        foreach($parcelasPendentes as $key => $subarray) {
            $parcelaPaga =  ParcelaPaga::from('parcela_paga')
                ->where("id_pendente",$parcelasPendentes[$key]->id)
                ->get();

            if ($parcelaPaga!='[]') {
                unset($parcelasPendentes[$key]);
            } else {
                $allParcelas = ParcelaPendente::with('despesa')
                    ->where('id_despesa', '=', $parcelasPendentes[$key]->despesa->id)
                    ->orderBy('dt_vencimento', 'asc')
                    ->get();

                $size = count($allParcelas);

                foreach($allParcelas as $key2 => $subsubarray) {
                    //$parcelasPendentes[$key]->referencia=$allParcelas;
                    if ( ($allParcelas[$key2]->id) == ($parcelasPendentes[$key]->id) ) {
                        $parcelasPendentes[$key]->referencia=($key2+1).'/'.$size;
                        break;
                    }
                }
            }
        }       

        return view('despesas/pendente',
            ['menuView'=>'pendentes',
                'page'=>'Despesas Pendentes',
                'parcelas'=>$parcelasPendentes,
                'categorias'=>$categorias,
                'cartoes'=>$cartoes,
                'contas'=>$contas,
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
        $usuarioLogado = $request->session()->get('usuarioLogado');
        $periodoSelecionadoInicio = $request->session()->get('periodoSelecionadoInicio');
        $periodoSelecionadoFim = $request->session()->get('periodoSelecionadoFim');
        $parcelasPendentes = ParcelaPendente::with(['despesa.categoria' => function ($query) use ($usuarioLogado) {
            $query->where('categoria.id_usuario', '=', $usuarioLogado->id);
        }])->whereBetween('dt_vencimento', [
            $periodoSelecionadoInicio,
            $periodoSelecionadoFim
        ])->get();
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
