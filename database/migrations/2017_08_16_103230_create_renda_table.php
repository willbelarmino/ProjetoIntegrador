<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRendaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('renda', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('nome', 50);
			$table->dateTime('dt_recebimento');
			$table->decimal('valor');
			$table->bigInteger('id_conta')->index('fk_Renda_Conta_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('renda');
	}

}
