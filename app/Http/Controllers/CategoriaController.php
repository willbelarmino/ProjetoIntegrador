<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Model\Categoria;
use function GuzzleHttp\Psr7\_parse_message;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;

class CategoriaController extends Controller
{



    public function index(Request $request){
        $usuarioLogado = 3;//$request->session()->get('usuarioLogado');
        $categorias = DB::table('categoria')->where('id_usuario', $usuarioLogado)->get();
        return view('categorias/categorias',['menuView'=>'categorias','page'=>'Categorias','categorias'=>$categorias]);
    }

    protected function create(Request $request){
        try {

            //$usuarioLogado = $request->session()->get('usuarioLogado');
            $param = $request->all();
            if (!empty($param['limite'])) {
                $limite = str_replace("R$", "", $param['limite']);
                $limite = str_replace(".", "", $limite);
                $limite = str_replace(",", ".", $limite);
                $new_categoria = Categoria::create([
                    'nome' => $param['nome'],
                    'limite' => $limite,
                    'id_usuario' => 3 //$usuarioLogado
                ]);
            } else {
                $new_categoria = Categoria::create([
                    'nome' => $param['nome'],
                    'id_usuario' => 3 //$usuarioLogado
                ]);
            }

            if (empty($new_categoria)) {
                throw new CustomException('Erro ao cadastrar categoria. Tente novamente mais tarde.');
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

    protected function delete(){

    }

    protected function edit(Request $request){
        try {
            $param = $request->all();
            if (empty($param['nome'])) {
                throw new CustomException("nome vazio");
            }

            if (empty($param['id'])) {
                throw new CustomException("id vazio");
            }

            if (!empty($param['limite'])) {
                $limite = str_replace("R$", "", $param['limite']);
                $limite = str_replace(".", "", $limite);
                $limite = str_replace(",", ".", $limite);
            } else {
                $limite = 25.5;
            }

            DB::table('categoria')
                ->where('id', $param['id'])
                ->update([
                    ['nome' => $param['nome']],
                    ['limite' => $limite]
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
                'message' =>  'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

}
