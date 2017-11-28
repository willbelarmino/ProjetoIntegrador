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

            try {
                $msg = "Usuário >".$user->email."< inseriu categoria: ".$new_categoria->nome;
                LogFacade::registrarLog($user,$msg);
            } catch (Exception $ex) {

            }
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
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

        } catch (Exception $e) {
             throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function deletarCategoria($categoria) {
        try {
           
            DB::table('categoria')->where('id',$categoria)->delete();         
            
              
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function editarCategoriaSemLimite($categoria, $nome) {
        try {           
            

            DB::table('categoria')
                ->where('id', $categoria)
                ->update([
                    'nome' => $nome
                   
            ]);
              
        } catch (Exception $e) {
             throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function getExtratoCategoria($categoria, $periodo) {
        try {           
            
            $parcelasPagas = DespesaFacade::getParcelasPagasPorCategoria($categoria, $periodo);  
            $parcelasPendentes = DespesaFacade::getParcelasPendentesPorCategoria($categoria, $periodo);  

            $extrato['data'] = [];

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
                      0 => date_format(date_create($parcelasPendentes[$key]->dt_vencimento),"d/m/Y"), 
                      1 => $parcelasPendentes[$key]->despesa->nome, 
                      2 => 'R$ '.number_format($parcelasPendentes[$key]->valor, 2, ',', '.'),
                      3 => 'Pendente'
                ); 
            } 
             
            return $extrato;  
              
        } catch (Exception $e) {
             throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }

    public static function gerarGraficosCategorias($user, $periodo) {
      try {

            $parcelasPagas =  DespesaFacade::getParcelasPagas($user, $periodo);  

            $valorTotalPagas =  DespesaFacade::getTotalDespesaPagaPeriodo($user, $periodo);

            $valorTotalRendas =  RendaFacade::getTotalRendasPeriodo($user, $periodo);

            $categorias = self::getCategorias($user);

            $grafico['categoria'] = [];
            $grafico['bars'] = [];

            $count = 0;

            foreach($categorias as $key => $subarray) {              
               $totalCategoria = 0.0;
               foreach($parcelasPagas as $key2 => $subarray2) {
                  if ($parcelasPagas[$key2]->parcelaPendente->despesa->id_categoria==$categorias[$key]->id) {
                      $totalCategoria = $totalCategoria + $parcelasPagas[$key2]->valor;
                      $count = 1;
                  } 
               }  

               if ($count==1) {
                  $porcentagem = round((($totalCategoria * 100) / $valorTotalPagas));
                  $grafico['categoria'][] = array (
                    'nome' => $categorias[$key]->nome, 
                    'porcentagem' => $porcentagem                    
                 );
               } 
               $count=0;                

            }
            
          //Session::put('periodoInicioGrafico', date("Y0101",  strtotime($periodo->periodoSelecionadoInicio)));
          //Session::put('periodoFinalGrafico', date("Y01t",  strtotime($periodo->periodoSelecionadoFim))); 
          
          /*    
          $periodoGraficoArray = [
              'periodoSelecionadoInicio' => date("Y0101",  strtotime($periodo->periodoSelecionadoInicio)), 
              'periodoSelecionadoFim' => date("Y01t",  strtotime($periodo->periodoSelecionadoFim))
          ];
          $periodoJson = json_encode($periodoGraficoArray);       


          for ($i = 1; $i <= 12; $i++) {              

              $valorTotalPagasMes =  DespesaFacade::getTotalDespesaPagaPeriodo($user, $periodoJson);
              $valorTotalRendasMes =  RendaFacade::getTotalRendasPeriodo($user, $periodoJson);
              
              $grafico['bars'][] = array (
                    'entrada' => $valorTotalRendasMes, 
                    'retirada' => $valorTotalPagasMes                    
              );

              $novoPeriodo = date('Ymd', strtotime("+1 month", strtotime($periodoJson->periodoSelecionadoInicio)));

              $periodoGraficoArray = [
                'periodoSelecionadoInicio' => date("Ym01",  strtotime($novoPeriodo)), 
                'periodoSelecionadoFim' => date("Ymt",  strtotime($novoPeriodo))
              ];    

              $periodoJson = json_encode($periodoGraficoArray);          
              ;
          }
          */
        
           return $grafico;

      } catch (Exception $ex) {
          return null;
      }
   }



}