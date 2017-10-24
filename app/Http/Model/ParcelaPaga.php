<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class ParcelaPaga extends Model
{
    public $timestamps = false;

    private $referencia;

    protected $guarded = ['id'];

    protected $table = 'parcela_paga';

    protected $fillable = ['valor', 'dt_pagamento', 'id_conta', 'id_pendente'];

    public function conta() {
        return $this->belongsTo('App\Http\Model\Conta', 'id_conta','id');
    }

    public function parcelaPendente() {
        return $this->belongsTo('App\Http\Model\ParcelaPendente', 'id_pendente','id');
    }
}
