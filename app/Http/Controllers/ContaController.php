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


class ContaController extends Controller
{

    public function index(Request $request){
        $usuarioLogado = $request->session()->get('usuarioLogado');
        $contas = DB::table('conta')->where('id_usuario', $usuarioLogado->id)->get();
        foreach($contas as $key => $subarray) {
            $contas[$key]->saldo=100.0;
        }
        return view('contas/contas',['menuView'=>'contas','page'=>'Contas','contas'=>$contas]);
    }

    protected function create(Request $request){
        try {


            $usuarioLogado = $request->session()->get('usuarioLogado');
            $param = $request->all();

            if (!empty($param['indicador']) &&  $param['indicador']=='on') {
                $indicador = 'S';
            } else {
                $indicador = 'N';
            }

            $new_conta = Conta::create([
                'nome' => $param['nome'],
                'tipo' => $param['tipo'],
                'exibir_indicador' => $indicador,
                'dt_movimento' => date('Ymd'),
                'id_usuario' => $usuarioLogado->id
            ]);


            $file = $request->file('image');
            if (!empty($file)) {
                $image = Image::make($file)->resize(128, 128)->encode('jpg')->stream();
                $file_image_name = '4'.time().'.jpg';
                Storage::disk('local-conta')->put($file_image_name,$image->__toString());
                DB::table('conta')->where('id', $new_conta->id)->update(['image' => $file_image_name]);
            }


            if (empty($new_conta)) {
                throw new CustomException('Ops. Erro ao cadastrar conta. Tente novamente mais tarde.');
            }

            return response()->json([
                'status' => 'success',
                'message' =>  'Conta cadastrada com sucesso.',
            ]);

        }catch (CustomException $ex) {
            return response()->json([
                'status' => 'error',
                'message' =>  $ex->getMessage()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ops. Ocorreu um erro inesperado. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function delete(Request $request){
        try {
            $param = $request->all();
            DB::table('conta')->where('id',$param['id'])->delete();
            return response()->json([
                'status' => 'success',
                'message' =>  'Conta removida com sucesso.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ops. Erro ao remover registro. Tente novamente mais tarde.'
            ]);
        }
    }

    protected function edit(Request $request){

    }

}