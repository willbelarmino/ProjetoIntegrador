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

            $rendas = Renda::with('conta')
                ->whereHas('conta', function($query) use ($user) {
                    $query->where('conta.id_usuario', '=', $user->id);
                })
                ->whereBetween('dt_recebimento', [
                    $periodo->periodoSelecionadoInicio,
                    $periodo->periodoSelecionadoFim
                ])->get();


            return $rendas;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function populaRendas($user, $periodo) {
        try {

            $tabela['data'] = [];             

            // Rendas Fixas

            $rendasFixa = RendaFixa::with('conta')
                ->whereHas('conta', function($query) use ($user) {
                    $query->where('conta.id_usuario', '=', $user->id);
                })
                ->where([
                    ['dt_recebimento_inicio', '<', $periodo->periodoSelecionadoFim],
                    ['dt_cancelamento', '=', null]
                ])->orWhere([
                    ['dt_recebimento_inicio', '<', $periodo->periodoSelecionadoFim],
                    ['dt_cancelamento', '>', $periodo->periodoSelecionadoFim]
                ])->get();

            foreach($rendasFixa as $key2 => $subarray2) {

                $id = $rendasFixa[$key2]->id;
                $nome = $rendasFixa[$key2]->nome;
                $data_recebimento = date_format(date_create($rendasFixa[$key2]->dt_recebimento_inicio),"d/m/Y");
                $valor = 'R$ '.number_format($rendasFixa[$key2]->valor, 2, ',', '.');
                $contaNome = $rendasFixa[$key2]->conta->nome;
                $contaID = $rendasFixa[$key2]->id_conta;
                $isFixa = 'true';

                $tabela['data'][] = array (
                    0 => $nome."   <span class='btn btn-warning btn-xs' style='cursor: default !important;'> Fixa </span>",
                    1 => $data_recebimento,
                    2 => $valor,
                    3 => $contaNome,
                    4 =>
                        "<button type='button' rel='tooltip' style='margin: 2px;' title='visualizar' class='btn btn-info btn-simple'"
                        ."onclick='visualizar(
                                \"".$nome."\",  
                                \"".$valor."\", 
                                \"".$data_recebimento."\",  
                                \"".$contaNome."\" 
                            )'>"
                        ."<i class='material-icons'>assignment</i>"

                        ."</button>"

                        ."<button type='button' rel='tooltip' style='margin: 2px;' title='Cancelar' class='btn btn-warning btn-simple'"
                        ."onclick='cancelar(
                                \"".$id."\" 
                            )'>"
                        ."<i class='material-icons'>cancel</i>"

                        ."</button>"

                        ."<button type='button' rel='tooltip' style='margin: 2px;' title='deletar' class='btn btn-danger btn-simple'"
                        ."onclick='deletar(
                                \"".$id."\",
                                \"".$isFixa."\" 
                            )'>"
                        ."<i class='mdi mdi-delete' style='font-size: 17px !important;'></i>"

                        ."</button>"


                );
            }
            
            // Rendas Normais


            $rendas = Renda::with('conta')
                ->whereHas('conta', function($query) use ($user) {
                    $query->where('conta.id_usuario', '=', $user->id);
                })
                ->whereBetween('dt_recebimento', [
                    $periodo->periodoSelecionadoInicio,
                    $periodo->periodoSelecionadoFim
                ])->get();



            foreach($rendas as $key => $subarray) {
                $id = $rendas[$key]->id;
                $nome = $rendas[$key]->nome;
                $data_recebimento = date_format(date_create($rendas[$key]->dt_recebimento),"d/m/Y");
                $valor = 'R$ '.number_format($rendas[$key]->valor, 2, ',', '.');
                $contaNome = $rendas[$key]->conta->nome;
                $contaID = $rendas[$key]->id_conta;
                $isFixa = 'false';

                $tabela['data'][] = array (
                    0 => $nome,
                    1 => $data_recebimento,
                    2 => $valor,
                    3 => $contaNome,
                    4 =>
                        "<button type='button' rel='tooltip' style='margin: 2px;' title='visualizar' class='btn btn-info btn-simple'"
                            ."onclick='visualizar(
                                \"".$nome."\",  
                                \"".$valor."\", 
                                \"".$data_recebimento."\",  
                                \"".$contaNome."\" 
                            )'>"
                            ."<i class='material-icons'>assignment</i>"

                        ."</button>"

                        ."<button type='button' rel='tooltip' style='margin: 2px;' title='alterar' class='btn btn-success btn-simple'"
                        ."onclick='alterar(
                                \"".$id."\",  
                                \"".$nome."\", 
                                \"".$valor."\",  
                                \"".$data_recebimento."\",
                                \"".$contaID."\",  
                                \"".$contaNome."\"
                            )'>"
                        ."<i class='material-icons'>edit</i>"
                        ."<div class='ripple-container'></div>"
                        ."</button>"

                        ."<button type='button' rel='tooltip' style='margin: 2px;' title='deletar' class='btn btn-danger btn-simple'"
                        ."onclick='deletar(
                                \"".$id."\",
                                \"".$isFixa."\"  
                            )'>"
                        ."<i class='mdi mdi-delete' style='font-size: 17px !important;'></i>"

                        ."</button>"


                );
            }


            return $tabela;

        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage()." : ".$rendasFixa);
        }
    }

    public static function getRendasPorConta($conta, $periodo) {
        try {

            $rendas = Renda::with('conta')
                ->whereBetween('dt_recebimento', [
                    $periodo->periodoSelecionadoInicio,
                    $periodo->periodoSelecionadoFim
                ])->where('id_conta', '=', $conta)
                ->get();

            return $rendas;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function getRendasFixaPorConta($conta, $periodo) {
        try {

            $rendasFixa = RendaFixa::with('conta')
            ->where([
                ['id_conta', '=', $conta],
                ['dt_recebimento_inicio', '<', $periodo->periodoSelecionadoFim],
                ['dt_cancelamento', '=', null]
            ])->orWhere([
                ['id_conta', '=', $conta],
                ['dt_recebimento_inicio', '<', $periodo->periodoSelecionadoFim],
                ['dt_cancelamento', '>', $periodo->periodoSelecionadoFim]
            ])->get();

            return $rendasFixa;
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

    public static function deletarRendaFixa($renda){
        try {

            DB::table('renda_fixa')->where('id',$renda)->delete();

        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function cancelarRendaFixa($renda){
        try {
            DB::table('renda_fixa')
                ->where('id', $renda)
                ->update([
                    'dt_cancelamento' => date("Y-m-d")
                ]);
        } catch (Exception $e) {

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