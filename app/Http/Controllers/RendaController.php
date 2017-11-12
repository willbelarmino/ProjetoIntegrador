<?php

namespace App\Http\Controllers;

use App\Http\Facade\RendaFacade;
use App\Http\Facade\ContaFacade;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;
use PDF;
use App;


class RendaController extends Controller
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

    public function index(Request $request) {

        try {
            $usuarioLogado = self::getUsuario();
            $periodo = self::getPeriodo();
            if ($periodo == null || $usuarioLogado == null) {
                throw new Exception();
            }                        

            $rendas = RendaFacade::getRendas($usuarioLogado, $periodo);  

            $contas = ContaFacade::getContas($usuarioLogado);

            return view('rendas/rendas',
                ['menuView'=>'rendas',
                    'page'=>'Rendas',                
                    'rendas'=>$rendas,
                    'contas'=>$contas,
                    'nomeMes'=>$periodo->mes,
                    'resize'=>$periodo->resize,
                    'usuario'=>$usuarioLogado
                ]);

        } catch (Exception $ex) {
            return view ('rendas/error');
        }

    }

 
    protected function create(Request $request) {
        try {

            $usuarioLogado = self::getUsuario();
            $param = $request->all();
                                    
            try {

                RendaFacade::criarRenda($param['nome'], $param['valor'], $param['recebimento'], $param['conta']);

                return response()->json([
                    'status' => 'success',
                    'message' =>  'Renda cadastrada com sucesso.'
                ]);

            } catch (CustomException $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' =>  'Ops. Erro ao cadastrar renda. Tente novamente mais tarde.'
                ]);
            }
        
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' =>  'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }


    protected function delete(Request $request) {
        try {
           
            $param = $request->all();
            
            try {

                 RendaFacade::deletarRenda($param['id']);  

                return response()->json([
                    'status' => 'success',
                    'message' =>  'Renda removida com sucesso.'
                ]);

            } catch (CustomException $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ops. Erro ao remover renda. Tente novamente mais tarde.'
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
        try {

            $param = $request->all();

            try {

                RendaFacade::editarRenda($param['id'], $param['nome'], $param['valor'], $param['recebimento'], $param['conta']); 
               
                return response()->json([
                    'status' => 'success',
                    'message' =>  'Renda alterada com sucesso.'
                ]);

            } catch (CustomException $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ops. Erro ao alterar renda. Tente novamente mais tarde.'
                ]);  
            }  

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ops. correu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function toPDF(Request $request){
        try {

            $usuarioLogado = self::getUsuario();
            $periodo = self::getPeriodo();
            $rendas = RendaFacade::getRendas($usuarioLogado, $periodo);  
            $pdf = PDF::loadView('rendas/relatorios/renda-rel', ['link'=>$rendas, 'title'=>'Rendas']);
            return $pdf->stream();

        } catch (Exception $e) {
            
        }
    }



}
