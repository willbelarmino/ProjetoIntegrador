<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'usuario';

    protected $fillable = ['nome', 'email', 'senha', 'image', 'bloquear_limite_categoria', 'unificar_indicadores_convidado'];
}
