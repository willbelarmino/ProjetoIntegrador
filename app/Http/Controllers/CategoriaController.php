<?php

namespace App\Http\Controllers;

use App\Http\Model\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;
use PDF;
use App;
use Barryvdh\Snappy;

class CategoriaController extends Controller
{



    public function index(Request $request){
        $usuarioLogado = $request->session()->get('usuarioLogado');
        $categorias = DB::table('categoria')->where('id_usuario', $usuarioLogado->id)->get();
        $nomeMes = UtilsController::getNomeMesSelecionado($request);
        return view('categorias/categorias',[
            'menuView'=>'categorias',
            'page'=>'Categorias',
            'categorias'=>$categorias,
            'usuario'=>$usuarioLogado,
            'nomeMes'=>$nomeMes
        ]);
    }

    protected function create(Request $request){
        try {

            $usuarioLogado = $request->session()->get('usuarioLogado');
            $param = $request->all();
            if (!empty($param['limite']) && $param['limite']!="R$ 0,00") {
                $limite = str_replace("R$", "", $param['limite']);
                $limite = str_replace(".", "", $limite);
                $limite = str_replace(",", ".", $limite);
                $new_categoria = Categoria::create([
                    'nome' => $param['nome'],
                    'limite' => $limite,
                    'id_usuario' => $usuarioLogado->id
                ]);
            } else {
                $new_categoria = Categoria::create([
                    'nome' => $param['nome'],
                    'id_usuario' => $usuarioLogado->id
                ]);
            }

            if (empty($new_categoria)) {
                throw new CustomException('Ops. Erro ao cadastrar categoria. Tente novamente mais tarde.');
            }

            return response()->json([
                'status' => 'success',
                'message' =>  'Categoria cadastrada com sucesso.'
            ]);

        }catch (CustomException $ex) {
            return response()->json([
                'status' => 'error',
                'message' =>  $ex->getMessage()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' =>  'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function delete(Request $request){
        try {
            $param = $request->all();
            DB::table('categoria')->where('id',$param['id'])->delete();
            return response()->json([
                'status' => 'success',
                'message' =>  'Categoria removida com sucesso.'
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

            if (!empty($param['limite'])) {
                $limite = str_replace("R$", "", $param['limite']);
                $limite = str_replace(".", "", $limite);
                $limite = str_replace(",", ".", $limite);
            } else {
                $limite = null;
            }

            DB::table('categoria')
                ->where('id', $param['id'])
                ->update([
                    'nome' => $param['nome'],
                    'limite' => $limite
                ]);

            return response()->json([
                'status' => 'success',
                'message' =>  'Categoria alterada com sucesso.'
            ]);
        } catch (CustomException $ex) {
            return response()->json([
                'status' => 'error',
                'message' =>  $ex->getMessage()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' =>  'Ops. Erro ao alterar registro. Tente novamente mais tarde.'
            ]);
        }
    }

}
