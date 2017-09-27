<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class CartaoCredito extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'cartao_credito';

    protected $fillable = ['limite', 'dt_fechamento', 'dt_vencimento','id_conta','cartao_independente'];

    public function conta() {
        return $this->belongsTo('App\Http\Model\Conta', 'id_conta');
    }

    public function despesas() {
        return $this->hasMany('App\Http\Model\Despesa');
    }

}
