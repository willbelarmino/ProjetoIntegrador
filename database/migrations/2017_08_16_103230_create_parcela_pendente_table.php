<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateParcelaPendenteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('parcela_pendente', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->decimal('valor');
			$table->dateTime('dt_vencimento');
			$table->bigInteger('id_despesa')->index('fk_Parcela_Pendente_Despesa1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('parcela_pendente');
	}

}
