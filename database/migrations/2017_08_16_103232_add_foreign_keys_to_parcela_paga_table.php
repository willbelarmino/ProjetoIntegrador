<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToParcelaPagaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('parcela_paga', function(Blueprint $table)
		{
			$table->foreign('id_conta', 'fk_Parcelas_Conta1')->references('id')->on('conta')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('id_pendente', 'fk_Parcela_Paga_Parcela_Pendente1')->references('id')->on('parcela_pendente')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('parcela_paga', function(Blueprint $table)
		{
			$table->dropForeign('fk_Parcelas_Conta1');
			$table->dropForeign('fk_Parcela_Paga_Parcela_Pendente1');
		});
	}

}
