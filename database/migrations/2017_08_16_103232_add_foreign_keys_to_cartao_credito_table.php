<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCartaoCreditoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cartao_credito', function(Blueprint $table)
		{
			$table->foreign('id_conta', 'fk_Cartao_Credito_Conta1')->references('id')->on('conta')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('cartao_credito', function(Blueprint $table)
		{
			$table->dropForeign('fk_Cartao_Credito_Conta1');
		});
	}

}
