<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCompartilhamentoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('compartilhamento', function(Blueprint $table)
		{
			$table->foreign('id_usuario_master', 'fk_Usuario_has_Usuario_Usuario1')->references('id')->on('usuario')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('id_usuario_convidado', 'fk_Usuario_has_Usuario_Usuario2')->references('id')->on('usuario')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('compartilhamento', function(Blueprint $table)
		{
			$table->dropForeign('fk_Usuario_has_Usuario_Usuario1');
			$table->dropForeign('fk_Usuario_has_Usuario_Usuario2');
		});
	}

}
