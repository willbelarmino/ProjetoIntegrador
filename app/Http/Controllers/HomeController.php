<?php
namespace App\Http\Controllers;
use App\Http\Facade\RendaFacade;
use App\Http\Facade\DespesaFacade;
use App\Http\Facade\ContaFacade;
use App\Http\Facade\CategoriaFacade;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;
use PDF;
use App;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $usuarioLogado = self::getUsuario();
            $periodo = self::getPeriodo();

            $totalRenda =  RendaFacade::getTotalRendasPeriodo($usuarioLogado, $periodo);

            $totalDespesaPendente =  DespesaFacade::getTotalDespesaPendentePeriodo($usuarioLogado, $periodo);

            $totalDespesaPaga =  DespesaFacade::getTotalDespesaPagaPeriodo($usuarioLogado, $periodo);

            $saldoAtual =  UtilsController::getSaldoAtual($usuarioLogado, $periodo);

            $saldoEstimado =  UtilsController::getSaldoEstimado($usuarioLogado, $periodo);

            $contas = ContaFacade::getContasParaExibicao($usuarioLogado, $periodo);

            return view('home',
                ['menuView'=>'dashboard',
                    'page'=>'Dashboard',
                    'totalRenda'=>$totalRenda,
                    'totalDespesaPendente'=>$totalDespesaPendente,
                    'totalDespesaPaga'=>$totalDespesaPaga,
                    'saldoAtual'=>$saldoAtual,
                    'saldoEstimado'=>$saldoEstimado,
                    'contas'=>$contas,
                    'nomeMes'=>$periodo->mes,
                    'resize'=>$periodo->resize,
                    'usuario'=>$usuarioLogado
                ]);

        } catch (Exception $e) {
            return view('error',
                ['menuView'=>'dashboard',
                    'page'=>'Dashboard',
                    'usuario'=>$usuarioLogado,
                    'message' => $e->getMessage()
                ]);
        }

    }

    public function visualizarExtratoConta(Request $request) {
        try {

            $param = $request->all();

            $periodo = self::getPeriodo();

            $extrato = ContaFacade::getExtratoConta($param['id'], $periodo);

            return response()->json($extrato);

        } catch (Exception $e) {
            return response()->json([
                'data' => 'error'
            ]);
        }
    }


    protected function toPDF(Request $request){
        try {

            $usuarioLogado = self::getUsuario();
            $periodo = self::getPeriodo();
            

            $pdf = PDF::loadView('relatorios/extratoConta', ['title'=>'Extrato']);
            return $pdf->stream();

        } catch (Exception $e) {

        }
    }

    protected function gerarGraficos(Request $request){
        try {

            $usuarioLogado = self::getUsuario();
            $periodo = self::getPeriodo();

            $grafico = CategoriaFacade::gerarGraficosCategorias($usuarioLogado, $periodo);

            return response()->json($grafico);
           
        } catch (Exception $e) {
            return response()->json([
                'categoria' => 'error'                
            ]);
        }
    }








}