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

            $image = Image::make($file)->resize(128, 128)->encode('jpg')->stream();
            $file_image_name_old = $imagem;
            $file_image_name = $conta.time().'.jpg';
            Storage::disk('local-conta')->delete($file_image_name_old);
            Storage::disk('local-conta')->put($file_image_name,$image->__toString());

            DB::table('usuario')
                ->where('id', $id)
                ->update([
                    'nome' => $nome,
                    'senha' => $senha,
                    'image' => $file_image_name
            ]);

        } catch (Exception $ex) {
            return null;
        }
    }


}