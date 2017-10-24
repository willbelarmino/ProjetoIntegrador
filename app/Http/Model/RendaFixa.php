<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class RendaFixa extends Model
{
   	public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'renda_fixa';

    private $saldo;

    protected $fillable = ['nome', 'dt_recebimento_inicio', 'dt_cancelamento', 'valor','id_conta'];

    public function conta() {
        return $this->belongsTo('App\Http\Model\Conta', 'id_conta', 'id');
    }
}
