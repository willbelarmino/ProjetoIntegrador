<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRendaFixaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('renda_fixa', function(Blueprint $table)
		{
			$table->foreign('id_conta', 'fk_RendaFixa_Conta1')->references('id')->on('conta')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('renda_fixa', function(Blueprint $table)
		{
			$table->dropForeign('fk_RendaFixa_Conta1');
		});
	}

}
