<?php
/**
 * Created by PhpStorm.
 * User: will_
 * Date: 16/10/2017
 * Time: 20:09
 */

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;
use PDF;
use App;
use Session;
use Auth;

class UtilsController extends Controller
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

    public static function getUsuarioLogado() {
        try {
            $usuario =  Auth::user();
            return $usuario;
        } catch (Exception $ex) {
            return null;
        }
    }

    public static function getPeriodo()  {
        try {
            $periodoSelecionadoInicio = Session::get('periodoSelecionadoInicio');
            $periodoSelecionadoFim = Session::get('periodoSelecionadoFim');

            $mesInicio = substr($periodoSelecionadoInicio, -4, -2);
            $mesFim = substr($periodoSelecionadoFim, -4, -2);
            
            // Periodo Mensal
            if ($mesInicio == $mesFim) {
                $mes = array('', 'Janeiro', 'Fevereiro',
                    'Março', 'Abril', 'Maio', 'Junho', 'Julho',
                    'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

                if (substr($periodoSelecionadoInicio, -4, -3) == '0') {
                    $mesNumber = substr($periodoSelecionadoInicio, -3, -2);
                } else {
                    $mesNumber = substr($periodoSelecionadoInicio, -4, -2);
                }
                return response()->json([
                    'mes' => $mes[$mesNumber] . " - " . substr($periodoSelecionadoInicio, -8, -4),
                    'resize' => '130',
                    'periodoSelecionadoInicio' => $periodoSelecionadoInicio,
                    'periodoSelecionadoFim' => $periodoSelecionadoFim
                ]);

            // Periodo Por Data
            } else {
                return response()->json([
                    'mes' => date('d/m/Y', strtotime($periodoSelecionadoInicio)) . " - " . date('d/m/Y', strtotime($periodoSelecionadoFim)),
                    'resize' => '165',
                    'periodoSelecionadoInicio' => $periodoSelecionadoInicio,
                    'periodoSelecionadoFim' => $periodoSelecionadoFim
                ]);
            }
        } catch (Exception $ex) {
            return null;
        }
    }

    public function alterarPeriodoMes (Request $request) {
        try {
            $param = $request->all();
            $periodoSelecionadoInicio = $request->session()->get('periodoSelecionadoInicio');
            if ($param['id']=='next') {
                $novoPeriodo = date('Ymd', strtotime("+1 month", strtotime($periodoSelecionadoInicio)));// date("Ymd", strtotime(date("Y-m-d", strtotime($periodoSelecionadoFim)) . " +1 month"));
            } else {
                $novoPeriodo = date('Ymd', strtotime("-1 month", strtotime($periodoSelecionadoInicio)));//date("Ymd", strtotime(date("Y-m-d", strtotime($periodoSelecionadoInicio)) . " -1 month"));
            }
            $request->session()->put('periodoSelecionadoInicio', date("Ym01",  strtotime($novoPeriodo)));
            $request->session()->put('periodoSelecionadoFim', date("Ymt",  strtotime($novoPeriodo)));

            if (substr($novoPeriodo, -4, -3) == '0') {
                $mesNumber = substr($novoPeriodo, -3, -2);
            } else {
                $mesNumber = substr($novoPeriodo, -4, -2);
            }
            $mes = array('', 'Janeiro', 'Fevereiro',
                'Março', 'Abril', 'Maio', 'Junho', 'Julho',
                'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

            return response()->json([
                'status' => 'success',
                'nomeMes' => $mes[$mesNumber]." - ".substr($novoPeriodo, -8, -4),
                'message' =>  'Mês alterado com sucesso!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Falha ao alterar data!'
            ]);
        }
    }

    public function alterarPeriodoData (Request $request) {
        try {
            $param = $request->all();

            $diaInicio = substr($param['inicio'], 0, -8);
            $mesInicio = substr($param['inicio'], 3, -5);
            $anoInicio = substr($param['inicio'], -4);
            $inicio = $anoInicio.$mesInicio.$diaInicio;

            $diaFinal = substr($param['final'], 0, -8);
            $mesFinal = substr($param['final'], 3, -5);
            $anoFinal = substr($param['final'], -4);
            $final = $anoFinal.$mesFinal.$diaFinal;


            $request->session()->put('periodoSelecionadoInicio', $inicio);
            $request->session()->put('periodoSelecionadoFim', $final);

            return response()->json([
                'status' => 'success',
                'nomeMes' => date('d/m/Y', strtotime($inicio))." - ".date('d/m/Y', strtotime($final)),
                'message' =>  'Período alterado com sucesso.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Falha ao alterar o período!'
            ]);
        }
    }

    public static function getSaldoAtual($user, $periodo) {
        try {
            
            $saldoAtual =  DB::select("SELECT showSaldoAtual(
                   $periodo->periodoSelecionadoInicio,
                   $periodo->periodoSelecionadoFim,                 
                   $user->id
            ) AS saldoAtual ");

            $saldoAtual = json_decode(json_encode($saldoAtual), true);
            
            return $saldoAtual[0]['saldoAtual'];

        } catch (Exception $e) {
            throw new CustomException();
        }
    } 

    public static function getSaldoEstimado($user, $periodo) {
        try {
            
            $saldoEstimado =  DB::select("SELECT showSaldoEstimado(
                   $periodo->periodoSelecionadoInicio,
                   $periodo->periodoSelecionadoFim,                 
                   $user->id
            ) AS saldoEstimado ");
            
            $saldoEstimado = json_decode(json_encode($saldoEstimado), true);
            
            return $saldoEstimado[0]['saldoEstimado'];

        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public function error(Request $request) {
        $usuarioLogado = self::getUsuarioLogado();
        return view('error',
            ['menuView'=>'dashboard',
                'page'=>'Dashboard',
                'usuario'=>$usuarioLogado,
                'message' => 'Erro!'
            ]);
    }

}