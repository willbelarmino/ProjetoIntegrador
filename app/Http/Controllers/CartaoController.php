<?php


namespace App\Http\Controllers;

use App\Http\Model\CartaoCredito;
use App\Http\Model\Conta;
use Carbon\Carbon;
use DateTime;
use App\Http\Model\Despesa;
use App\Http\Model\Usuario;
use App\Http\Model\ParcelaPendente;
use App\Http\Model\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class CartaoController extends Controller
{

    public function index(Request $request){
        $usuarioLogado = $request->session()->get('usuarioLogado');
        $periodo = UtilsController::getPeriodo($request);
        $periodo = $periodo->getData();
        $cartoes = CartaoCredito::from('cartao_credito AS cc')
            ->join('conta AS c','cc.id_conta','=','c.id')
            ->where("c.id_usuario",$usuarioLogado->id)
            ->select('cc.*')
            ->get();

        $contas = DB::table('conta')->where('id_usuario', $usuarioLogado->id)->get();
     
        return view('cartoes/cartoes',[
            'menuView'=>'cartoes',
            'page'=>'Cartões',
            'cartoes'=>$cartoes,
            'contas'=>$contas,
            'usuario'=>$usuarioLogado,
            'nomeMes'=>$periodo->mes,
            'resize'=>$periodo->resize
        ]);
    }

    protected function create(Request $request){
        try {
            $usuarioLogado = $request->session()->get('usuarioLogado');
            $param = $request->all();

            $limite = str_replace("R$", "", $param['limite']);
            $limite = str_replace(".", "", $limite);
            $limite = str_replace(",", ".", $limite);

            $dia = substr($param['vencimento'], 0, -8);
            $mes = substr($param['vencimento'], 3, -5);
            $ano = substr($param['vencimento'], -4);
            $vencimento = $ano.$mes.$dia;

            $diaF = substr($param['fechamento'], 0, -8);
            $mesF = substr($param['fechamento'], 3, -5);
            $anoF = substr($param['fechamento'], -4);
            $fechamento = $anoF.$mesF.$diaF;

            if (!empty($param['independente']) &&  $param['independente']=='true') {
                $new_conta = Conta::create([
                    'nome' => $param['nome'],
                    'tipo' => 'O',
                    'exibir_indicador' => 'N',
                    'dt_movimento' => date('Ymd'),
                    'id_usuario' => $usuarioLogado->id
                ]);

                if (empty($new_conta)) {
                    throw new CustomException('Ops. Erro ao cadastrar cartão. Tente novamente mais tarde.');
                }

                $new_cartao = CartaoCredito::create([
                    'limite' => $limite,
                    'dt_fechamento' => $fechamento,
                    'dt_vencimento' => $vencimento,
                    'id_conta' => $new_conta->id,
                    'cartao_independente' => true
                ]);
            } else {
                $new_cartao = CartaoCredito::create([
                    'limite' => $limite,
                    'dt_fechamento' => $fechamento,
                    'dt_vencimento' => $vencimento,
                    'id_conta' => $param['conta'],
                    'cartao_independente' => false
                ]);
            }

            if (empty($new_cartao)) {
                throw new CustomException('Ops. Erro ao cadastrar cartão. Tente novamente mais tarde.');
            }

            return response()->json([
                'status' => 'success',
                'message' =>  'Cartão cadastrado com sucesso.',
            ]);

        }catch (CustomException $ex) {
            return response()->json([
                'status' => 'error',
                'message' =>  $param['independente']
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $param['independente'] //'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function delete(Request $request){
        try {
            $param = $request->all();

            if ($param['independente']=='1') {
                DB::table('cartao_credito')->where('id',$param['id'])->delete();
                DB::table('conta')->where('id',$param['conta'])->delete();
            } else {
                DB::table('cartao_credito')->where('id',$param['id'])->delete();
            }
            return response()->json([
                'status' => 'success',
                'message' =>  'Cartão removido com sucesso.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() //'Ops. Erro ao remover registro. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function edit(Request $request){
        try {
            $usuarioLogado = $request->session()->get('usuarioLogado');
            $param = $request->all();

            $limite = str_replace("R$", "", $param['limite']);
            $limite = str_replace(".", "", $limite);
            $limite = str_replace(",", ".", $limite);

            $dia = substr($param['vencimento'], 0, -8);
            $mes = substr($param['vencimento'], 3, -5);
            $ano = substr($param['vencimento'], -4);
            $vencimento = $ano.$mes.$dia;

            $diaF = substr($param['fechamento'], 0, -8);
            $mesF = substr($param['fechamento'], 3, -5);
            $anoF = substr($param['fechamento'], -4);
            $fechamento = $anoF.$mesF.$diaF;

            DB::table('cartao_credito')
                ->where('id', $param['id'])
                ->update([
                    'limite' => $limite,
                    'dt_fechamento' => $fechamento,
                    'dt_vencimento' => $vencimento
                ]);

            return response()->json([
                'status' => 'success',
                'message' =>  'Cartão alterado com sucesso.',
            ]);

        }catch (CustomException $ex) {
            return response()->json([
                'status' => 'error',
                'message' =>  $ex->getMessage()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() //'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

}