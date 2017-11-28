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

class UsuarioFacade
{

    public static function alterarDadosComImagem($id, $nome, $senha, $file) {
        try {

            $avatar = Image::make($file)->resize(128, 128)->encode('jpg')->stream();
            $file_avatar_name = $id.time().'.jpg';
            Storage::disk('local-avatar')->put($file_avatar_name,$avatar->__toString());            

            $new_user_profile = DB::table('usuario')
                ->where('id', $id)
                ->update([
                    'nome' => $nome,
                    'senha' => $senha,
                    'image' => $file_avatar_name
            ]);

            if (empty($new_user_profile)) {
                throw new Exception();
            } else {
                return $new_user_profile;
            }

        } catch (Exception $ex) {
            return null;
        }
    }

    public static function alterarDadosSemImagem($id, $nome, $senha) {
        try {

            $new_user_profile = DB::table('usuario')
                ->where('id', $id)
                ->update([
                    'nome' => $nome,
                    'senha' => $senha,
                    'image' => 'avatar_default.jpg'
            ]);

            if (empty($new_user_profile)) {
                throw new Exception();
            } else {
                return $new_user_profile;
            }

        } catch (Exception $ex) {
            return null;
        }
    }

    public static function encerrarCadastro($user) {
        try {
           
            //DB::table('parcela')->where('id',$categoria)->delete();         
                          
        } catch (Exception $e) {
            throw new Exception("Erro Facade: ".$e->getMessage());
        }
    }


}