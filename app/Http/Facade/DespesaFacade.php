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
use Illuminate\Support\Facades\File;
use PDF;
use App;
use Barryvdh\Snappy;
use Session;
use App\Http\Model\ParcelaPendente;
use App\Http\Model\ParcelaPaga;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class DespesaFacade
{

    public static function getParcelasPendentes($user, $periodo) {
        try {

            $parcelasPendentes = ParcelaPendente::with('despesa.categoria')
                ->whereHas('despesa.categoria', function($query) use ($user) {
                    $query->where('categoria.id_usuario', '=', $user->id);
                })->whereBetween('dt_vencimento', [
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

    public static function getParcelasPendentesPorCategoria($categoria, $periodo) {
        try {

            $parcelasPendentes = ParcelaPendente::with('despesa')
                ->whereHas('despesa', function($query) use ($categoria) {
                    $query->where('despesa.id_categoria', '=', $categoria);
                })->whereBetween('dt_vencimento', [
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

            $parcelasPagas = ParcelaPaga::with('parcelaPendente.despesa.categoria')
                ->whereHas('parcelaPendente.despesa.categoria', function($query) use ($user) {
                    $query->where('categoria.id_usuario', '=', $user->id);
                })->whereBetween('dt_pagamento', [
                    $periodo->periodoSelecionadoInicio,
                    $periodo->periodoSelecionadoFim
                ])->get();

            return $parcelasPagas;
        } catch (Exception $e) {
            return null;
        }
    }


    public static function getParcelasPagasPorConta($conta, $periodo) {
        try {

            $parcelasPagas = ParcelaPaga::from('parcela_paga AS p')
                ->where([
                    ['id_conta', '=', $conta]
                ])->whereBetween('dt_pagamento', [
                    $periodo->periodoSelecionadoInicio,
                    $periodo->periodoSelecionadoFim
                ])->get();

            return $parcelasPagas;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function getParcelasPendentesPorCartao($cartao, $periodo) {
        try {

                $parcelasPendentes = ParcelaPendente::with('despesa')
                ->whereHas('despesa', function($query) use ($cartao) {
                    $query->where('despesa.id_cartao_credito', '=', $cartao);
                })->whereBetween('dt_vencimento', [
                    $periodo->periodoSelecionadoInicio,
                    $periodo->periodoSelecionadoFim
                ])->get();

                foreach($parcelasPendentes as $key => $subarray) {
                    $parcelaPaga =  ParcelaPaga::from('parcela_paga')
                        ->where("id_pendente",$parcelasPendentes[$key]->id)
                        ->get();

                    if ($parcelaPaga!='[]') {
                        unset($parcelasPendentes[$key]);
                    } 
                }

            return $parcelasPendentes;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function getParcelasPagasPorCategoria($categoria, $periodo) {
        try {

            $parcelasPagas = ParcelaPaga::with('parcelaPendente.despesa')
                ->whereHas('parcelaPendente.despesa', function($query) use ($categoria) {
                    $query->where('despesa.id_categoria', '=', $categoria);
                })->whereBetween('dt_pagamento', [
                    $periodo->periodoSelecionadoInicio,
                    $periodo->periodoSelecionadoFim
                ])->get();

            return $parcelasPagas;
        } catch (Exception $e) {
            return null;
        }
    }
    

    public static function criarDespesaPendenteSemCredito($nome, $valor, $venc, $parcela, $cat){
        try {
           
            if (!empty($valor) && $valor!="R$ 0,00") {
                $valor = str_replace("R$", "", $valor);
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor);

                $dia = substr($venc, 0, -8);
                $mes = substr($venc, 3, -5);
                $ano = substr($venc, -4);
                $vencimento = $ano.$mes.$dia;

                DB::select("CALL criarDespesaPendente(
                    '".$nome."', 
                    ".$valor.", 
                    ".$parcela." ,
                    '".$vencimento."',
                    ".$cat.",
                    null)"
                );

            } else {
                throw new Exception("Favor, selecione um valor valido.");
            }
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function criarDespesaPendenteComCredito($nome, $valor, $venc, $parcela, $cat, $cred){
        try {
           
            if (!empty($valor) && $valor!="R$ 0,00") {
                $valor = str_replace("R$", "", $valor);
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor);

                $dia = substr($venc, 0, -8);
                $mes = substr($venc, 3, -5);
                $ano = substr($venc, -4);
                $vencimento = $ano.$mes.$dia;

                DB::select("CALL criarDespesaPendente(
                    '".$nome."', 
                    ".$valor.", 
                    ".$parcela." ,
                    '".$vencimento."',
                    ".$cat.",
                    ".$cred.")"
                );

            } else {
                throw new Exception("Favor, selecione um valor valido.");
            }
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function criarDespesaPagaComCredito($nome, $valor, $pag, $parcela, $cat, $cred, $cont){
        try {
           
           if (!empty($valor) && $valor!="R$ 0,00") {
                $valor = str_replace("R$", "", $valor);
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor);

                $dia = substr($pag, 0, -8);
                $mes = substr($pag, 3, -5);
                $ano = substr($pag, -4);
                $pagamento = $ano.$mes.$dia;

                DB::select("CALL criarDespesaPaga(
                    '".$nome."', 
                    ".$valor.", 
                    ".$parcela." ,
                    '".$pagamento."',
                    ".$cat.",
                    ".$cont.",
                    ".$cred.",
                     @new_paga
                )");

            } else {
                throw new Exception("Favor, selecione um valor valido.");
            }
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function criarDespesaPagaComCreditoFile($nome, $valor, $pag, $parcela, $cat, $cred, $cont, $file){
        try {
           
           if (!empty($valor) && $valor!="R$ 0,00") {
                $valor = str_replace("R$", "", $valor);
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor);

                $dia = substr($pag, 0, -8);
                $mes = substr($pag, 3, -5);
                $ano = substr($pag, -4);
                $pagamento = $ano.$mes.$dia;

                DB::select("CALL criarDespesaPaga(
                    '".$nome."', 
                    ".$valor.", 
                    ".$parcela." ,
                    '".$pagamento."',
                    ".$cat.",
                    ".$cont.",
                    ".$cred.",
                    @new_paga
                )");

                $paga = DB::select("SELECT @new_paga as paga");
                $paga = json_decode(json_encode($paga), true);
                $paga = $paga[0]['paga'];

                $file_image_name = $paga.time().'.pdf';
                $content = File::get($file);
                Storage::disk('local-comprovante')->put($file_image_name,$content);
                DB::table('parcela_paga')->where('id', $paga)->update(['comprovante' => $file_image_name]);

            } else {
                throw new Exception("Favor, selecione um valor valido.");
            }
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function criarDespesaPagaSemCreditoFile($nome, $valor, $pag, $parcela, $cat, $cont, $file){
        try {
           
           if (!empty($valor) && $valor!="R$ 0,00") {
                $valor = str_replace("R$", "", $valor);
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor);

                $dia = substr($pag, 0, -8);
                $mes = substr($pag, 3, -5);
                $ano = substr($pag, -4);
                $pagamento = $ano.$mes.$dia;

                DB::select("CALL criarDespesaPaga(
                    '".$nome."', 
                    ".$valor.", 
                    ".$parcela." ,
                    '".$pagamento."',
                    ".$cat.",
                    ".$cont.",
                    null,
                    @new_paga
                )");

                $paga = DB::select("SELECT @new_paga as paga");
                $paga = json_decode(json_encode($paga), true);
                $paga = $paga[0]['paga'];

                $file_image_name = $paga.time().'.pdf';
                $content = File::get($file);
                Storage::disk('local-comprovante')->put($file_image_name,$content);
                DB::table('parcela_paga')->where('id', $paga)->update(['comprovante' => $file_image_name]);

            } else {
                throw new Exception("Favor, selecione um valor valido.");
            }
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }


    public static function criarDespesaPagaSemCredito($nome, $valor, $pag, $parcela, $cat, $cont){
        try {
           
           if (!empty($valor) && $valor!="R$ 0,00") {
                $valor = str_replace("R$", "", $valor);
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor);

                $dia = substr($pag, 0, -8);
                $mes = substr($pag, 3, -5);
                $ano = substr($pag, -4);
                $pagamento = $ano.$mes.$dia;

                DB::select("CALL criarDespesaPaga(
                    '".$nome."', 
                    ".$valor.", 
                    ".$parcela." ,
                    '".$pagamento."',
                    ".$cat.",
                    ".$cont.",
                    null,
                    @new_paga
                )");

            } else {
                throw new Exception("Favor, selecione um valor valido.");
            }
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }


    public static function editarDespesaPendenteSemCredito($despesa, $nome, $valor, $venc, $cat){
        try {
           
            $valor = str_replace("R$", "", $valor);
            $valor = str_replace(".", "", $valor);
            $valor = str_replace(",", ".", $valor);

            $dia = substr($venc, 0, -8);
            $mes = substr($venc, 3, -5);
            $ano = substr($venc, -4);
            $vencimento = $ano.$mes.$dia;

            DB::select("CALL alterarDespesaPendente(                    
                    ".$despesa.",
                    '".$nome."',
                    ".$valor.",
                    '".$vencimento."',
                    ".$cat.",
                    null)");
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function editarDespesaPendenteComCredito($despesa, $nome, $valor, $venc, $cat, $cred){
        try {
           
            $valor = str_replace("R$", "", $valor);
            $valor = str_replace(".", "", $valor);
            $valor = str_replace(",", ".", $valor);

            $dia = substr($venc, 0, -8);
            $mes = substr($venc, 3, -5);
            $ano = substr($venc, -4);
            $vencimento = $ano.$mes.$dia;

             DB::select("CALL alterarDespesaPendente(                    
                    ".$despesa.",
                    '".$nome."',
                    ".$valor.",
                    '".$vencimento."',
                    ".$cat.",
                    ".$cred.")");
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function deletarDespesaPendente($despesa, $periodo){
        try {
           
           DB::select("CALL excluirDespesaPendente(                    
                    ".$despesa.",
                    '".$periodo->periodoSelecionadoInicio."',
                    '".$periodo->periodoSelecionadoFim."')");
            
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function deletarDespesaPaga($id){
        try {
           DB::table('parcela_paga')->where('id',$id)->delete();  
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function pagarDespesa($pendente, $conta){
        try {            
            
            DB::select("CALL pagarDespesa(                    
                        " . $pendente . ",
                        " . $conta . ",
                        @new_paga
                        )");            

        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function pagarDespesaComComprovante($pendente, $conta, $file){
        try {

             DB::select("CALL pagarDespesa(                    
                        " . $pendente . ",
                        " . $conta . ",
                        @new_paga
                        )");

            $paga = DB::select("SELECT @new_paga as paga");
            $paga = json_decode(json_encode($paga), true);
            $paga = $paga[0]['paga'];

            $file_image_name = $paga.time().'.pdf';
            $content = File::get($file);
            Storage::disk('local-comprovante')->put($file_image_name,$content);
            DB::table('parcela_paga')->where('id', $paga)->update(['comprovante' => $file_image_name]);


        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
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
            return null;
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
            return null;
        }
    }






}