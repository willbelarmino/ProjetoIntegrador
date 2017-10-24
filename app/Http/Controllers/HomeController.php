<?php

namespace App\Http\Controllers;

use App\Http\Model\CartaoCredito;
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
use PDF;
use App;
use Barryvdh\Snappy;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $usuarioLogado = $request->session()->get('usuarioLogado');
            $periodo = UtilsController::getPeriodo($request);
            $periodo = $periodo->getData();

            $totalRenda =  DB::select("SELECT totalRendasDoMes(
                   $periodo->periodoSelecionadoInicio,
                   $periodo->periodoSelecionadoFim,                 
                   $usuarioLogado->id
            ) AS totalRenda ");
            $totalRenda = json_decode(json_encode($totalRenda), true);

            $totalDespesaPendente =  DB::select("SELECT totalDespesasPendentesDoMes(
                   $periodo->periodoSelecionadoFim, 
                   $usuarioLogado->id
            ) AS totalDespesaPendente ");
            $totalDespesaPendente = json_decode(json_encode($totalDespesaPendente), true);

            $totalDespesaPaga =  DB::select("SELECT totalDespesasPagasDoMes(
                   $periodo->periodoSelecionadoInicio,
                   $periodo->periodoSelecionadoFim,                 
                   $usuarioLogado->id
            ) AS totalDespesaPaga ");
            $totalDespesaPaga = json_decode(json_encode($totalDespesaPaga), true);

            $saldoAtual =  DB::select("SELECT showSaldoAtual(
                   $periodo->periodoSelecionadoInicio,
                   $periodo->periodoSelecionadoFim,                 
                   $usuarioLogado->id
            ) AS saldoAtual ");
            $saldoAtual = json_decode(json_encode($saldoAtual), true);

            $saldoEstimado =  DB::select("SELECT showSaldoEstimado(
                   $periodo->periodoSelecionadoInicio,
                   $periodo->periodoSelecionadoFim,                 
                   $usuarioLogado->id
            ) AS saldoEstimado ");
            $saldoEstimado = json_decode(json_encode($saldoEstimado), true);
           



        } catch (Exception $e) {
            $totalRenda = "ERRO";
        }



        return view('home/home',
            ['menuView'=>'dashboard',
                'page'=>'Dashboard',
                'totalRenda'=>$totalRenda[0]['totalRenda'],
                'totalDespesaPendente'=>$totalDespesaPendente[0]['totalDespesaPendente'],
                'totalDespesaPaga'=>$totalDespesaPaga[0]['totalDespesaPaga'],
                'saldoAtual'=>$saldoAtual[0]['saldoAtual'],
                'saldoEstimado'=>$saldoEstimado[0]['saldoEstimado'],
                'nomeMes'=>$periodo->mes,
                'resize'=>$periodo->resize,
                'usuario'=>$usuarioLogado
            ]);
    }
}
