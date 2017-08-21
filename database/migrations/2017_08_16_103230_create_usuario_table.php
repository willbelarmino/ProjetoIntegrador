<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsuarioTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('usuario', function(Blueprint $table)
		{

			$table->bigInteger('id', true);
			$table->string('nome', 50);
			$table->string('email', 50)->unique('email_UNIQUE');
			$table->string('senha', 200);
			$table->binary('image', 65535)->nullable();
			$table->boolean('bloquear_limite_categoria');
			$table->boolean('unificar_indicadores_convidado');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('usuario');
	}

}
