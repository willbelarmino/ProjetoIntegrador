<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRendaFixaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('renda_fixa', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('nome', 45);
			$table->dateTime('dt_recebimento_inicio');
			$table->dateTime('dt_cancelamento')->nullable();
			$table->decimal('valor');
			$table->bigInteger('id_conta')->index('fk_RendaFixa_Conta1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('renda_fixa');
	}

}
