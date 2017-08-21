<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRendaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('renda', function(Blueprint $table)
		{
			$table->foreign('id_conta', 'fk_Renda_Conta')->references('id')->on('conta')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('renda', function(Blueprint $table)
		{
			$table->dropForeign('fk_Renda_Conta');
		});
	}

}
