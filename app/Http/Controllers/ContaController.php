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


class ContaController extends Controller
{

    public function index(Request $request){
        $usuarioLogado = $request->session()->get('usuarioLogado');
        $contas = DB::table('conta')->where('id_usuario', $usuarioLogado->id)->get();
        return view('contas/contas',['menuView'=>'contas','page'=>'Contas','contas'=>$contas]);
    }

    protected function create(Request $request){

    }

    protected function delete(Request $request){

    }

    protected function edit(Request $request){

    }

}