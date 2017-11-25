<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCartaoCreditoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cartao_credito', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->decimal('limite');
			$table->integer('dt_fechamento');
			$table->integer('dt_vencimento');
			$table->bigInteger('id_conta')->index('fk_Cartao_Credito_Conta1_idx');
			$table->boolean('cartao_independente');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cartao_credito');
	}

}
