<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateParcelaPagaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('parcela_paga', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->decimal('valor');
			$table->dateTime('dt_pagamento');
			$table->bigInteger('id_conta')->index('fk_Parcelas_Conta1_idx');
			$table->bigInteger('id_pendente')->index('fk_Parcela_Paga_Parcela_Pendente1');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('parcela_paga');
	}

}
