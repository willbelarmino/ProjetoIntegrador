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

class ContaFacade
{

    public static function getContas($user) {
        try {

        	$contas = DB::table('conta')->where('id_usuario', $user->id)->get();	
        	foreach($contas as $key => $subarray) {
            	$contas[$key]->saldo=100.0;
      		}
            return  $contas;

        } catch (Exception $ex) {
            return null;
        }
    }

    public static function criarConta($nome, $tipo, $indicador, $file, $user) {
        try {

            if (!empty($indicador) &&  $indicador=='on') {
                $indicador = 'S';
            } else {
                $indicador = 'N';
            }

            $new_conta = Conta::create([
                'nome' => $nome,
                'tipo' => $tipo,
                'exibir_indicador' => $indicador,
                'dt_movimento' => date('Ymd'),
                'id_usuario' => $user->id
            ]);

            if (!empty($file)) {
                $image = Image::make($file)->resize(128, 128)->encode('jpg')->stream();
                $file_image_name = $new_conta->id.time().'.jpg';
                Storage::disk('local-conta')->put($file_image_name,$image->__toString());
                DB::table('conta')->where('id', $new_conta->id)->update(['image' => $file_image_name]);
            }

            if (empty($new_conta)) {
                throw new Exception();
            }

        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function deletarConta($conta) {
        try {
           
            DB::table('conta')->where('id',$conta)->delete(); 
              
        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function editarConta($conta, $nome, $indicador, $imagem, $tipo, $file) {
        try {
           
            if (!empty($indicador) &&  $indicador=='on') {
                $indicador = 'S';
            } else {
                $indicador = 'N';
            }           
            
            if (!empty($file)) {
                $image = Image::make($file)->resize(128, 128)->encode('jpg')->stream();
                $file_image_name_old = $imagem;
                $file_image_name = $conta.time().'.jpg';
                Storage::disk('local-conta')->delete($file_image_name_old);
                Storage::disk('local-conta')->put($file_image_name,$image->__toString());
            }

            DB::table('conta')
                ->where('id', $conta)
                ->update([
                    'nome' => $nome,
                    'tipo' => $tipo,
                    'exibir_indicador' => $indicador,
                    'image' => $file_image_name
            ]);
            
        } catch (Exception $e) {
            throw new CustomException();
        }
    }


    public static function getExtratoConta($conta, $periodo) {
        try {
           
            $rendas = RendaFacade::getRendasPorConta($conta, $periodo);
            $parcelasPagas = DespesaFacade::getParcelasPagasPorConta($conta, $periodo);            
            $extrato = [];

            
            foreach($rendas as $key => $subarray) {                
                $extrato['data'][] = array (
                      0 => date_format(date_create($rendas[$key]->dt_recebimento),"d/m/Y"),
                      1 => $rendas[$key]->nome, 
                      2 => 'R$ '.number_format($rendas[$key]->valor, 2, ',', '.')
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
            throw new CustomException($e->getMessage());
        }
    }





}