<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateObjetivoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('objetivo', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('descricao', 80);
			$table->decimal('valor_total');
			$table->decimal('meta');
			$table->dateTime('dt_prevista_final');
			$table->bigInteger('id_usuario')->index('fk_Objetivo_Usuario1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('objetivo');
	}

}
