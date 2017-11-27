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
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ContaFacade
{

    public static function atualizaDataMovimento($conta) {
        try {
            DB::table('conta')
                ->where('id', $conta)
                ->update([
                    'dt_movimento' => date("Y-m-d H:i:s")
                ]);
        } catch (Exception $e) {

        }
    }

    public static function getContas($user, $periodo) {
        try {

        	$contas = DB::table('conta')->where([['id_usuario', $user->id],['tipo','<>','I']])->get();
        	foreach($contas as $key => $subarray) {
        	    $saldo = 0.0;

        	    // Rendas
                $rendas = RendaFacade::getRendasPorConta($contas[$key]->id,$periodo);
                foreach($rendas as $keyR => $subarrayR) {
                    $saldo = $saldo + $rendas[$keyR]->valor;
                }

                // Rendas Fixa
                $rendasFixa = RendaFacade::getRendasFixaPorConta($contas[$key]->id,$periodo);
                foreach($rendasFixa as $keyRF => $subarrayRF) {
                    $saldo = $saldo + $rendasFixa[$keyRF]->valor;
                }

                // Despesas
                $despesas = DespesaFacade::getParcelasPagasPorConta($contas[$key]->id,$periodo);
                foreach($despesas as $keyD => $subarrayD) {
                    $saldo = $saldo - $despesas[$keyD]->valor;
                }

                $contas[$key]->saldo = $saldo;
      		}
            return  $contas;

        } catch (Exception $ex) {
            return null;
        }
    }

    public static function getContasParaExibicao($user, $periodo) {
        try {

            $contas = DB::table('conta')->where([['id_usuario', $user->id],['exibir_indicador','S']])->get();
            foreach($contas as $key => $subarray) {
                $saldo = 0.0;

                // Rendas
                $rendas = RendaFacade::getRendasPorConta($contas[$key]->id,$periodo);
                foreach($rendas as $keyR => $subarrayR) {
                    $saldo = $saldo + $rendas[$keyR]->valor;
                }

                // Rendas Fixa
                $rendasFixa = RendaFacade::getRendasFixaPorConta($contas[$key]->id,$periodo);
                foreach($rendasFixa as $keyRF => $subarrayRF) {
                    $saldo = $saldo + $rendasFixa[$keyRF]->valor;
                }

                // Despesas
                $despesas = DespesaFacade::getParcelasPagasPorConta($contas[$key]->id,$periodo);
                foreach($despesas as $keyD => $subarrayD) {
                    $saldo = $saldo - $despesas[$keyD]->valor;
                }

                $contas[$key]->saldo = $saldo;
            }
            return  $contas;

        } catch (Exception $ex) {
            return null;
        }
    }

    public static function criarContaComImagem($nome, $tipo, $indicador, $file, $user) {
        try {    

            $new_conta = Conta::create([
                'nome' => $nome,
                'tipo' => $tipo,
                'exibir_indicador' => $indicador,
                'dt_movimento' => date('Ymd'),
                'id_usuario' => $user->id                
            ]);          

            
            $image = Image::make($file)->resize(128, 128)->encode('jpg')->stream();
            $file_image_name = $new_conta->id.time().'.jpg';
            Storage::disk('local-conta')->put($file_image_name,$image->__toString());            
            DB::table('conta')->where('id', $new_conta->id)->update(['image' => $file_image_name]);            
            
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function criarContaSemImagem($nome, $tipo, $indicador, $user) {
        try {           

            $new_conta = Conta::create([
                'nome' => $nome,
                'tipo' => $tipo,
                'exibir_indicador' => $indicador,
                'dt_movimento' => date('Ymd'),
                'id_usuario' => $user->id
            ]);
         

        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function deletarConta($conta) {
        try {
           
            DB::table('conta')->where('id',$conta)->delete(); 
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function editarContaComImagem($conta, $nome, $indicador, $imagem, $tipo, $file) {
        try {   
            
            $image = Image::make($file)->resize(128, 128)->encode('jpg')->stream();
            $file_image_name_old = $imagem;
            $file_image_name = $conta.time().'.jpg';
            Storage::disk('local-conta')->delete($file_image_name_old);
            Storage::disk('local-conta')->put($file_image_name,$image->__toString());

            DB::table('conta')
                ->where('id', $conta)
                ->update([
                    'nome' => $nome,
                    'tipo' => $tipo,
                    'exibir_indicador' => $indicador,
                    'image' => $file_image_name
            ]);
            
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function editarContaSemImagem($conta, $nome, $indicador, $tipo) {
        try {  

            DB::table('conta')
                ->where('id', $conta)
                ->update([
                    'nome' => $nome,
                    'tipo' => $tipo,
                    'exibir_indicador' => $indicador,                    
            ]);
            
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }


    public static function getExtratoConta($conta, $periodo) {
        try {
           
            $rendas = RendaFacade::getRendasPorConta($conta, $periodo);
            $rendasFixa = RendaFacade::getRendasFixaPorConta($conta, $periodo);
            $parcelasPagas = DespesaFacade::getParcelasPagasPorConta($conta, $periodo);
            $extrato['data'] = [];

            
            foreach($rendas as $key => $subarray) {                
                $extrato['data'][] = array (
                      0 => date_format(date_create($rendas[$key]->dt_recebimento),"d/m/Y"),
                      1 => $rendas[$key]->nome, 
                      2 => 'R$ '.number_format($rendas[$key]->valor, 2, ',', '.')
                );                 
            }

            foreach($rendasFixa as $key3 => $subarray3) {
                $extrato['data'][] = array (
                    0 => date_format(date_create($rendasFixa[$key3]->dt_recebimento_inicio),"d/m/Y"),
                    1 => $rendasFixa[$key3]->nome,
                    2 => 'R$ '.number_format($rendasFixa[$key3]->valor, 2, ',', '.')
                );
            }
            
            foreach($parcelasPagas as $key2 => $subarray2) {
                $extrato['data'][] = array (
                      0 => date_format(date_create($parcelasPagas[$key2]->dt_pagamento),"d/m/Y"), 
                      1 => $parcelasPagas[$key2]->parcelaPendente->despesa->nome, 
                      2 => 'R$ '.number_format($parcelasPagas[$key2]->valor, 2, ',', '.')
                ); 
            } 
             
            return $extrato;  
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }





}