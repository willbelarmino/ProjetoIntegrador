<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToParcelaPendenteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('parcela_pendente', function(Blueprint $table)
		{
			$table->foreign('id_despesa', 'fk_Parcela_Pendente_Despesa1')->references('id')->on('despesa')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('parcela_pendente', function(Blueprint $table)
		{
			$table->dropForeign('fk_Parcela_Pendente_Despesa1');
		});
	}

}
