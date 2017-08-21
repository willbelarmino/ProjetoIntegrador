<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToObjetivoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('objetivo', function(Blueprint $table)
		{
			$table->foreign('id_usuario', 'fk_Objetivo_Usuario1')->references('id')->on('usuario')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('objetivo', function(Blueprint $table)
		{
			$table->dropForeign('fk_Objetivo_Usuario1');
		});
	}

}
