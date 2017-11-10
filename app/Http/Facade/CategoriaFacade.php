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

}