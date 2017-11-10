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

}