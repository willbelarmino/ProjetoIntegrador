<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompartilhamentoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('compartilhamento', function(Blueprint $table)
		{
			$table->bigInteger('id_usuario_master')->index('fk_Usuario_has_Usuario_Usuario1');
			$table->bigInteger('id_usuario_convidado')->index('fk_Usuario_has_Usuario_Usuario2');
			$table->string('funcionalidade', 50);
			$table->integer('permissao');
			$table->char('status', 1);
			$table->primary(['id_usuario_master','id_usuario_convidado']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('compartilhamento');
	}

}
