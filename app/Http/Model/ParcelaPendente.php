<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class ParcelaPendente extends Model
{
    public $timestamps = false;

    private $referencia;

    protected $guarded = ['id'];

    protected $table = 'parcela_pendente';

    protected $fillable = ['valor', 'dt_vencimento', 'id_despesa'];

    public function despesa() {
        return $this->belongsTo('App\Http\Model\Despesa', 'id_despesa','id');
    }
}
