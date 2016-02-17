<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceQuotasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_quotas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('number', 10);
			$table->mediumInteger('num')->unsigned()->default(0);
			$table->integer('agent_id')->unsigned();
			$table->date('issue_date');
			$table->date('due_date');
			$table->integer('edition_id')->unsigned();
			$table->double('adjustment');
			$table->timestamps();
            $table->softDeletes();
			
			// Foreign keys
			$table->foreign('agent_id')
			    ->references('id')
			    ->on('agents');
			$table->foreign('edition_id')
			    ->references('id')
			    ->on('editions');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoice_quotas');
	}

}
