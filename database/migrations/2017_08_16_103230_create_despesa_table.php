<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDespesaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('despesa', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('nome', 50);
			$table->decimal('valor_total');
			$table->integer('parcelas');
			$table->bigInteger('id_categoria')->index('fk_Despesa_Categoria1_idx');
			$table->bigInteger('id_cartao_credito')->nullable()->index('fk_Despesa_Cartao_Credito1');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('despesa');
	}

}
