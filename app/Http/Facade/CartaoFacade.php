<?php
/**
 * Created by PhpStorm.
 * User: will_
 * Date: 16/10/2017
 * Time: 20:09
 */

namespace App\Http\Facade;

use App\Http\Model\Categoria;
use App\Http\Model\Conta;
use App\Http\Model\CartaoCredito;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\CustomException;
use PDF;
use App;
use Barryvdh\Snappy;
use Session;
use App\Http\Model\ParcelaPendente;
use App\Http\Model\ParcelaPaga;

class CartaoFacade
{

    public static function getCartoes($user) {
        try {

            $cartoes = CartaoCredito::with('conta')
                ->whereHas('conta', function($query) use ($user) {
                    $query->where('conta.id_usuario', '=', $user->id);
                })
                ->get();

            return $cartoes;

        } catch (Exception $ex) {
            return null;
        }
    }

    public static function getCartao($id) {
        try {

            $cartao = CartaoCredito::from('cartao_credito AS cc')
                ->join('conta AS c','cc.id_conta','=','c.id')
                ->where("cc.id", $id)
                ->select('cc.*')
                ->get();

            return $cartao;

        } catch (Exception $ex) {
            return null;
        }
    }

    public static function criarCartaoIndependente($limite, $venc, $fech, $nome, $user) {
        try {

            $limite = str_replace("R$", "", $limite);
            $limite = str_replace(".", "", $limite);
            $limite = str_replace(",", ".", $limite);

            $new_conta = Conta::create([
                'nome' => $nome,
                'tipo' => 'I',
                'exibir_indicador' => 'N',
                'dt_movimento' => date('Ymd'),
                'id_usuario' => $user->id
            ]);

            $new_cartao = CartaoCredito::create([
                'limite' => $limite,
                'dt_fechamento' => $fech,
                'dt_vencimento' => $venc,
                'id_conta' => $new_conta->id,
                'cartao_independente' => true
            ]);

        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function criarCartao($limite, $venc, $fech, $conta) {
        try {
           
            $limite = str_replace("R$", "", $limite);
            $limite = str_replace(".", "", $limite);
            $limite = str_replace(",", ".", $limite);

            $new_cartao = CartaoCredito::create([
                'limite' => $limite,
                'dt_fechamento' => $fech,
                'dt_vencimento' => $venc,
                'id_conta' => $conta,
                'cartao_independente' => false
            ]);
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }


    public static function deletarCartao($cartao, $indep, $conta) {
        try {
           
           if ($indep=='1') {
                DB::table('cartao_credito')->where('id',$cartao)->delete();
                DB::table('conta')->where('id',$conta)->delete();
            } else {
                DB::table('cartao_credito')->where('id',$cartao)->delete();
            }
            
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function editarCartao($cartao, $limite, $venc, $fech) {
        try {
           
            $limite = str_replace("R$", "", $limite);
            $limite = str_replace(".", "", $limite);
            $limite = str_replace(",", ".", $limite);

            $dia = substr($venc, 0, -8);
            $mes = substr($venc, 3, -5);
            $ano = substr($venc, -4);
            $vencimento = $ano.$mes.$dia;

            $diaF = substr($fech, 0, -8);
            $mesF = substr($fech, 3, -5);
            $anoF = substr($fech, -4);
            $fechamento = $anoF.$mesF.$diaF;

            DB::table('cartao_credito')
                ->where('id', $cartao)
                ->update([
                    'limite' => $limite,
                    'dt_fechamento' => $fechamento,
                    'dt_vencimento' => $vencimento
                ]);
           
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

   

    public static function buscarFaturas($id, $periodo) {
        try {

            $cartao = self::getCartao($id);
            $parcelas = DespesaFacade::getParcelasPendentesPorCartao($cartao, $periodo);


        } catch (Exception $e) {

        }
        
    }



}