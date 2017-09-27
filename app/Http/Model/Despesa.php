<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'despesa';

    protected $fillable = ['nome', 'limite', 'id_usuario'];

    public function categoria() {
        return $this->belongsTo('App\Http\Model\Categoria', 'id_categoria','id');
    }

    public function cartao() {
        return $this->belongsTo('App\Http\Model\CartaoCredito', 'id_cartao_credito','id');
    }

    public function parcelasPendentes() {
        return $this->hasMany('App\Http\Model\ParcelaPendente');
    }
}
