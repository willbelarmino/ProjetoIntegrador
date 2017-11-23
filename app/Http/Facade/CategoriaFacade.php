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

class CategoriaFacade
{

   public static function getCategorias($user) {
      try {

           $categoria = Categoria::from('categoria AS c')
               ->where("c.id_usuario",$user->id)
               ->get();

           return $categoria;

      } catch (Exception $ex) {
          return null;
      }
   }

    public static function criarCategoriaSemLimite($nome, $user) {
        try {

            $new_categoria = Categoria::create([
                'nome' => $nome,
                'id_usuario' => $user->id
            ]);

            if (empty($new_categoria)) {
                throw new Exception();
            }
              
        } catch (Exception $e) {
            throw new CustomException("Erro Facade: ".$e->getMessage());
        }
    }

    public static function criarCategoriaComLimite($nome, $limite, $user) {
        try {
             $limite = str_replace("R$", "", $limite);
             $limite = str_replace(".", "", $limite);
             $limite = str_replace(",", ".", $limite);

             $new_categoria = Categoria::create([
                    'nome' => $nome,
                    'limite' => $limite,
                    'id_usuario' => $user->id
             ]);

            if (empty($new_categoria)) {
                throw new Exception();
            }

        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function deletarCategoria($categoria) {
        try {
           
            DB::table('categoria')->where('id',$categoria)->delete();          
            
              
        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function editarCategoria($categoria, $limite, $nome) {
        try {
           
            if ($limite!=null) {
                $limite = str_replace("R$", "", $limite);
                $limite = str_replace(".", "", $limite);
                $limite = str_replace(",", ".", $limite);
            }

            DB::table('categoria')
                ->where('id', $categoria)
                ->update([
                    'nome' => $nome,
                    'limite' => $limite
            ]);
              
        } catch (Exception $e) {
            throw new CustomException();
        }
    }

    public static function getExtratoCategoria($categoria, $periodo) {
        try {           
            
            $parcelasPagas = DespesaFacade::getParcelasPagasPorCategoria($categoria, $periodo);  
            $parcelasPendentes = DespesaFacade::getParcelasPendentesPorCategoria($categoria, $periodo);  

            $extrato = [];

            foreach($parcelasPagas as $key2 => $subarray2) {
                $extrato['data'][] = array (
                      0 => date_format(date_create($parcelasPagas[$key2]->dt_pagamento),"d/m/Y"), 
                      1 => $parcelasPagas[$key2]->parcelaPendente->despesa->nome, 
                      2 => 'R$ '.number_format($parcelasPagas[$key2]->valor, 2, ',', '.'),
                      3 => 'Pago'
                ); 
            } 

            foreach($parcelasPendentes as $key => $subarray) {
                $extrato['data'][] = array (
                      0 => date_format(date_create($parcelasPagas[$key]->dt_pagamento),"d/m/Y"), 
                      1 => $parcelasPagas[$key]->parcelaPendente->despesa->nome, 
                      2 => 'R$ '.number_format($parcelasPagas[$key]->valor, 2, ',', '.'),
                      3 => 'Pendente'
                ); 
            } 
             
            return $extrato;  
              
        } catch (Exception $e) {
            throw new CustomException($e->getMessage());
        }
    }



}