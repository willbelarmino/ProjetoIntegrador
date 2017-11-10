<?php
/**
 * Created by PhpStorm.
 * User: will_
 * Date: 16/10/2017
 * Time: 20:09
 */

namespace App\Http\Facade;

use App\Http\Model\Categoria;
use App\Http\Model\CartaoCredito;
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

class CartaoFacade
{

    public static function getCartoes($user) {
        try {

            $cartoes = CartaoCredito::from('cartao_credito AS cc')
                ->join('conta AS c','cc.id_conta','=','c.id')
                ->where("c.id_usuario",$user->id)
                ->select('cc.*')
                ->get();

            return $cartoes;

        } catch (Exception $ex) {
            return null;
        }
    }

}