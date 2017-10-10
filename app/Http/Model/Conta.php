<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'conta';

    private $saldo;

    protected $fillable = ['nome', 'image', 'dt_movimento','id_usuario', 'tipo', 'exibir_indicador'];

    public function cartoes() {
        return $this->hasMany('App\Http\Model\CartaoCredito');
    }
}
