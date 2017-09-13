<?php

/**
 * Created by PhpStorm.
 * User: WILLIAM
 * Date: 12/09/2017
 * Time: 23:44
 */
use Illuminate\Http\Request;
use Exception;
class UtilsSession
{
    public static function getUsuarioLogado(Request $request) {
        return $request->session()->get('usuarioLogado');
    }
}