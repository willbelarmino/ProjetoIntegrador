<?php
/**
 * Created by PhpStorm.
 * User: will_
 * Date: 16/10/2017
 * Time: 20:09
 */

namespace App\Http\Facade;

use App\Http\Model\Categoria;
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

class DespesaFacade
{

    public static function getParcelasPendentes($user, $periodo) {
        try {
            $parcelasPendentes = ParcelaPendente::with(['despesa.categoria' => function ($query) use ($user) {
                $query->where('categoria.id_usuario', '=', $user->id);
            }])->whereBetween('dt_vencimento', [
                $periodo->periodoSelecionadoInicio,
                $periodo->periodoSelecionadoFim
            ])->get();

            foreach($parcelasPendentes as $key => $subarray) {
                $parcelaPaga =  ParcelaPaga::from('parcela_paga')
                    ->where("id_pendente",$parcelasPendentes[$key]->id)
                    ->get();

                if ($parcelaPaga!='[]') {
                    unset($parcelasPendentes[$key]);
                } else {
                    $allParcelas = ParcelaPendente::with('despesa')
                        ->where('id_despesa', '=', $parcelasPendentes[$key]->despesa->id)
                        ->orderBy('dt_vencimento', 'asc')
                        ->get();

                    $size = count($allParcelas);

                    foreach($allParcelas as $key2 => $subsubarray) {
                        //$parcelasPendentes[$key]->referencia=$allParcelas;
                        if ( ($allParcelas[$key2]->id) == ($parcelasPendentes[$key]->id) ) {
                            $parcelasPendentes[$key]->referencia=($key2+1).'/'.$size;
                            break;
                        }
                    }
                }
            }

            return $parcelasPendentes;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function getParcelasPagas($user, $periodo) {
        try {

            $parcelasPagas = ParcelaPaga::with(['parcelaPendente.despesa.categoria' => function ($query) use ($user) {
                $query->where('categoria.id_usuario', '=', $user->id);
            }])->whereBetween('dt_pagamento', [
                $periodo->periodoSelecionadoInicio,
                $periodo->periodoSelecionadoFim
            ])->get();

            return $parcelasPagas;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function criarDespesaPendente($nome, $valor, $venc, $parcela, $cat, $cred){
        try {
           
            if (!empty($valor) && $valor!="R$ 0,00") {
                $valor = str_replace("R$", "", $valor);
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor);

                $dia = substr($venc, 0, -8);
                $mes = substr($venc, 3, -5);
                $ano = substr($venc, -4);
                $vencimento = $ano.$mes.$dia;

                if (!empty($cred)) {
                    DB::select("CALL criarDespesaPendente(
                    '".$nome."', 
                    ".$valor.", 
                    ".$parcela." ,
                    '".$vencimento."',
                    ".$cat.",
                    ".$cred.")");
                } else {
                    DB::select("CALL criarDespesaPendente(
                    '".$nome."', 
                    ".$valor.", 
                    ".$parcela." ,
                    '".$vencimento."',
                    ".$cat.",
                    null)");

                }

            } else {
                throw new Exception();
            }
              
        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function criarDespesaPaga($nome, $valor, $pag, $parcela, $cat, $cred, $cont){
        try {
           
           if (!empty($valor) && $valor!="R$ 0,00") {
                $valor = str_replace("R$", "", $valor);
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor);

                $dia = substr($pag, 0, -8);
                $mes = substr($pag, 3, -5);
                $ano = substr($pag, -4);
                $pagamento = $ano.$mes.$dia;

                if (!empty($cred)) {
                    DB::select("CALL criarDespesaPaga(
                    '".$nome."', 
                    ".$valor.", 
                    ".$parcela." ,
                    '".$pagamento."',
                    ".$cat.",
                    ".$cont.",
                    ".$cred.")");
                } else {
                    DB::select("CALL criarDespesaPaga(
                    '".$nome."', 
                    ".$valor.", 
                    ".$parcela." ,
                    '".$pagamento."',
                    ".$cat.",
                    ".$cont.",
                    null)");

                }

            } else {
                throw new Exception();
            }
              
        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function editarDespesaPendente($despesa, $nome, $valor, $venc, $cat, $cred){
        try {
           
            $valor = str_replace("R$", "", $valor);
            $valor = str_replace(".", "", $valor);
            $valor = str_replace(",", ".", $valor);

            $dia = substr($venc, 0, -8);
            $mes = substr($venc, 3, -5);
            $ano = substr($venc, -4);
            $vencimento = $ano.$mes.$dia;

            if (!empty($cred)) {
                DB::select("CALL alterarDespesaPendente(                    
                    ".$despesa.",
                    '".$nome."',
                    ".$valor.",
                    '".$vencimento."',
                    ".$cat.",
                    ".$cred.")");
            } else {
                DB::select("CALL alterarDespesaPendente(                    
                    ".$despesa.",
                    '".$nome."',
                    ".$valor.",
                    '".$vencimento."',
                    ".$cat.",
                    null)");
            }
              
        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function deletarDespesaPendente($despesa, $periodo){
        try {
           
           DB::select("CALL excluirDespesaPendente(                    
                    ".$despesa.",
                    '".$periodo->periodoSelecionadoInicio."',
                    '".$periodo->periodoSelecionadoFim."')");
            
              
        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function deletarDespesaPaga($despesa, $periodo){
        try {
           
           DB::select("CALL excluirDespesaPaga(                    
                    ".$despesa.",
                    '".$periodo->periodoSelecionadoInicio."',
                    '".$periodo->periodoSelecionadoFim."')");          
              
        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function pagarDespesa($pendente, $conta){
        try {            
            
            DB::select("CALL pagarDespesa(                    
                        " . $pendente . ",
                        " . $conta . "
                        )");            

        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function getTotalDespesaPendentePeriodo($user, $periodo) {
        try {
            
            $totalDespesaPendente =  DB::select("SELECT totalDespesasPendentesDoMes(
                   $periodo->periodoSelecionadoFim, 
                   $user->id
            ) AS totalDespesaPendente ");

            $totalDespesaPendente = json_decode(json_encode($totalDespesaPendente), true);

            return $totalDespesaPendente[0]['totalDespesaPendente'];

        } catch (Exception $e) {
            throw new CustomException();
        }
    } 

    public static function getTotalDespesaPagaPeriodo($user, $periodo) {
        try {
            
            $totalDespesaPaga =  DB::select("SELECT totalDespesasPagasDoMes(
                   $periodo->periodoSelecionadoInicio,
                   $periodo->periodoSelecionadoFim,                 
                   $user->id
            ) AS totalDespesaPaga ");

            $totalDespesaPaga = json_decode(json_encode($totalDespesaPaga), true);
            
            return $totalDespesaPaga[0]['totalDespesaPaga'];

        } catch (Exception $e) {
            throw new CustomException();
        }
    } 



}