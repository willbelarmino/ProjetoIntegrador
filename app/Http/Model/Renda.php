<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Renda extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'renda';

    private $saldo;

    protected $fillable = ['nome', 'dt_recebimento', 'valor','id_conta'];

    public function conta() {
        return $this->belongsTo('App\Http\Model\Conta', 'id_conta', 'id');
    }
}
