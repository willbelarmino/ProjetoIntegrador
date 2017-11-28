<?php


namespace App\Http\Controllers;


use App\Http\Facade\ContaFacade;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;



class ContaController extends Controller
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

            $contas = ContaFacade::getContas($usuarioLogado, $periodo);
            
            return view('contas/contas',[
                'menuView'=>'contas',
                'page'=>'Contas',
                'contas'=>$contas,
                'usuario'=>$usuarioLogado,
                'nomeMes'=>$periodo->mes,
                'resize'=>$periodo->resize
            ]);

        } catch (Exception $e) {
            return view ('contas/error');
        }
        
    }
    

    protected function create(Request $request){
        try {

            $usuarioLogado = self::getUsuario();
            $param = $request->all();
            $file = $request->file('image');
            if ($param['exibir']=="false") {
                $indicador = 'N';
            } else {
                $indicador = 'S';
            }         

            if (!empty($file)) {
                ContaFacade::criarContaComImagem($param['nome'], $param['tipo'], $indicador, $file, $usuarioLogado);
            } else {
                ContaFacade::criarContaSemImagem($param['nome'], $param['tipo'], $indicador, $usuarioLogado);
            }

            return response()->json([
                'status' => 'success',
                'message' =>  'Conta cadastrada com sucesso.',
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
                
            ContaFacade::deletarConta($param['id']);     

            return response()->json([
                'status' => 'success',
                'message' =>  'Conta removida com sucesso.',
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
            $file = $request->file('conta-view');

            if ($param['exibir']=="false") {
                $indicador = 'N';
            } else {
                $indicador = 'S';
            }            

            if (!empty($file)) {
                ContaFacade::editarContaComImagem(
                    $param['id'], 
                    $param['nome'], 
                    $indicador, 
                    $param['imagem'], 
                    $param['tipo'], 
                    $file
                );   
            } else {
                  
                ContaFacade::editarContaSemImagem(
                    $param['id'], 
                    $param['nome'], 
                    $indicador, 
                    $param['tipo']
                );  
            }                                 

            return response()->json([
                'status' => 'success',
                'message' =>  'Conta alterada com sucesso.',
            ]);         
        
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' =>  $e->getMessage() //'Ops. Erro ao alterar conta. Tente novamente mais tarde.'
            ]);
        }

    }

    

}