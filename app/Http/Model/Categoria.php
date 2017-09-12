<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'categoria';

    protected $fillable = ['nome', 'limite', 'id_usuario'];
}
