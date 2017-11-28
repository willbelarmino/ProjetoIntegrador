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
use App\Http\Model\Log;
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

class LogFacade
{

    public static function registrarLog($user, $desc) {
        try {

            Log::create([
                'descricao' => $desc,
                'id_usuario' => $user->id,
                'dt_log' => date("Y-m-d")
            ]);

        } catch (Exception $ex) {
            return null;
        }

    }




}