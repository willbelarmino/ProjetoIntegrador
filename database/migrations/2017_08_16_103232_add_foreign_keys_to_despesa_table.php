<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDespesaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('despesa', function(Blueprint $table)
		{
			$table->foreign('id_categoria', 'fk_Despesa_Categoria1')->references('id')->on('categoria')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('id_cartao_credito', 'fk_Despesa_Cartao_Credito1')->references('id')->on('cartao_credito')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('despesa', function(Blueprint $table)
		{
			$table->dropForeign('fk_Despesa_Categoria1');
			$table->dropForeign('fk_Despesa_Cartao_Credito1');
		});
	}

}
