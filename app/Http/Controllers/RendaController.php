<?php

namespace App\Http\Controllers;

use App\Http\Model\CartaoCredito;
use Carbon\Carbon;
use DateTime;
use App\Http\Model\Despesa;
use App\Http\Model\Usuario;
use App\Http\Model\ParcelaPendente;
use App\Http\Model\Renda;
use App\Http\Model\RendaFixa;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;
use PDF;
use App;
use Barryvdh\Snappy;

class RendaController extends Controller
{

    public function index(Request $request){
        $usuarioLogado = $request->session()->get('usuarioLogado');
        $periodo = UtilsController::getPeriodo($request);
        $periodo = $periodo->getData();


        $rendas = Renda::with(['conta' => function ($query) use ($usuarioLogado) {
            $query->where('conta.id_usuario', '=', $usuarioLogado->id);
        }])->whereBetween('dt_recebimento', [
            $periodo->periodoSelecionadoInicio,
            $periodo->periodoSelecionadoFim
        ])->get();

        $contas = DB::table('conta')->where('id_usuario', $usuarioLogado->id)->get();

        //$rendas = Renda::all();

        return view('rendas/rendas',
            ['menuView'=>'rendas',
                'page'=>'Rendas',                
                'rendas'=>$rendas,
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

                $dia = substr($param['recebimento'], 0, -8);
                $mes = substr($param['recebimento'], 3, -5);
                $ano = substr($param['recebimento'], -4);
                $recebimento = $ano.$mes.$dia;

                $new_renda = Renda::create([
                    'nome' => $param['nome'],
                    'valor' => $valor,
                    'dt_recebimento' => $recebimento,
                    'id_conta' => $param['conta']
                ]);

                if (empty($new_renda)) {
                throw new CustomException('Ops. Erro ao cadastrar renda. Tente novamente mais tarde.');
            }

            } else {
                throw new CustomException('Ops. O valor não pode ser R$ 0,00.');
            }

            return response()->json([
                'status' => 'success',
                'message' =>  'Renda cadastrada com sucesso.'
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
            $param = $request->all();
            DB::table('renda')->where('id',$param['id'])->delete();
            return response()->json([
                'status' => 'success',
                'message' =>  'Renda removida com sucesso.'
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

            $dia = substr($param['recebimento'], 0, -8);
            $mes = substr($param['recebimento'], 3, -5);
            $ano = substr($param['recebimento'], -4);
            $recebimento = $ano.$mes.$dia;

            DB::table('renda')
                ->where('id', $param['id'])
                ->update([
                    'nome' => $param['nome'],
                    'valor' => $valor,
                    'dt_recebimento' => $recebimento,
                    'id_conta' => $param['conta']
            ]);

            

            return response()->json([
                'status' => 'success',
                'message' =>  'Renda alterada com sucesso.'
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
        
        $rendas = Renda::with(['conta' => function ($query) use ($usuarioLogado) {
            $query->where('conta.id_usuario', '=', $usuarioLogado->id);
        }])->whereBetween('dt_recebimento', [
            $periodoSelecionadoInicio,
            $periodoSelecionadoFim
        ])->get();

        $pdf = PDF::loadView('rendas/relatorios/renda-rel', ['link'=>$rendas, 'title'=>'Rendas']);
        return $pdf->stream();
    }

}
