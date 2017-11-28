<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'log';

    protected $fillable = ['descricao', 'dt_log', 'id_usuario'];

    public function usuario() {
        return $this->belongsTo('App\Http\Model\Usuario','id_usuario', 'id');
    }
}
