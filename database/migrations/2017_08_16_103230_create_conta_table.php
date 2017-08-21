<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('conta', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('nome', 50);
			$table->binary('image', 65535)->nullable();
			$table->dateTime('dt_movimento');
			$table->bigInteger('id_usuario')->index('fk_Conta_Usuario1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('conta');
	}

}
