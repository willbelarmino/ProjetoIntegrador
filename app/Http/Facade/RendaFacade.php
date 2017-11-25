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

    public static function getRendasPorConta($conta, $periodo) {
        try {

            $rendas = Renda::with(['conta' => function ($query) use ($conta) {
                $query->where('conta.id', '=', $conta);
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
           
            if (!empty($valor) && $valor!="R$ 0,00") {
                $valor = str_replace("R$", "", $valor);
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor);

                $dia = substr($rec, 0, -8);
                $mes = substr($rec, 3, -5);
                $ano = substr($rec, -4);
                $recebimento = $ano.$mes.$dia;

                $new_renda = Renda::create([
                    'nome' => $nome,
                    'valor' => $valor,
                    'dt_recebimento' => $recebimento,
                    'id_conta' => $cont
                ]);               

            } else {
                throw new Exception("Favor, insira um valor valido.");
            }
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function criarRendaFixa($nome, $valor, $rec, $cont) {
        try {
           
            if (!empty($valor) && $valor!="R$ 0,00") {
                $valor = str_replace("R$", "", $valor);
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor);

                $dia = substr($rec, 0, -8);
                $mes = substr($rec, 3, -5);
                $ano = substr($rec, -4);
                $recebimento = $ano.$mes.$dia;

                $new_renda = RendaFixa::create([
                    'nome' => $nome,
                    'valor' => $valor,
                    'dt_recebimento_inicio' => $recebimento,
                    'id_conta' => $cont
                ]);               

            } else {
                throw new Exception("Favor, insira um valor valido.");
            }
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function deletarRenda($renda){
        try {
           
           DB::table('renda')->where('id',$renda)->delete();                     
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
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
            throw new Exception("Erro Facade: ".$e->getMessage());
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
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }  

}