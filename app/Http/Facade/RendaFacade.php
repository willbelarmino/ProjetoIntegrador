<?php
/**
 * Created by PhpStorm.
 * User: will_
 * Date: 16/10/2017
 * Time: 20:09
 */

namespace App\Http\Facade;

use App\Http\Model\Renda;
use App\Http\Model\RendaFixa;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Exceptions\CustomException;
use PDF;
use App;
use Session;


class RendaFacade
{

	public static function getRendas($user, $periodo) {
        try {

            $rendas = Renda::with(['conta' => function ($query) use ($user) {
                $query->where('conta.id_usuario', '=', $user->id);
            }])->whereBetween('dt_recebimento', [
                $periodo->periodoSelecionadoInicio,
                $periodo->periodoSelecionadoFim
            ])->get();

            return $rendas;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function criarRenda($nome, $valor, $rec, $cont) {
        try {
           
            if (!empty($param['valor']) && $param['valor']!="R$ 0,00") {
                $valor = str_replace("R$", "", $param['valor']);
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor);

                $dia = substr($param['recebimento'], 0, -8);
                $mes = substr($param['recebimento'], 3, -5);
                $ano = substr($param['recebimento'], -4);
                $recebimento = $ano.$mes.$dia;

                $new_renda = Renda::create([
                    'nome' => $param['nome'],
                    'valor' => $valor,
                    'dt_recebimento' => $recebimento,
                    'id_conta' => $param['conta']
                ]);

                if (empty($new_renda)) {
                throw new Exception();
            }

            } else {
                throw new Exception();
            }
              
        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function deletarRenda($renda){
        try {
           
           DB::table('renda')->where('id',$renda)->delete();                     
              
        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function editarRenda($renda, $nome, $valor, $rec, $cont){
        try {

           $valor = str_replace("R$", "", $valor);
            $valor = str_replace(".", "", $valor);
            $valor = str_replace(",", ".", $valor);

            $dia = substr($rec, 0, -8);
            $mes = substr($rec, 3, -5);
            $ano = substr($rec, -4);
            $recebimento = $ano.$mes.$dia;

            DB::table('renda')
                ->where('id', $renda)
                ->update([
                    'nome' => $nome,
                    'valor' => $valor,
                    'dt_recebimento' => $recebimento,
                    'id_conta' => $cont
            ]);
           
        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function getTotalRendasPeriodo($user, $periodo) {
        try {

            $totalRenda =  DB::select("SELECT totalRendasDoMes(
                $periodo->periodoSelecionadoInicio,
                $periodo->periodoSelecionadoFim,                 
                $user->id
            ) AS totalRenda ");
            
            $totalRenda = json_decode(json_encode($totalRenda), true);

            return $totalRenda[0]['totalRenda'];

        } catch (Exception $e) {
            throw new CustomException();
        }
    }  

}