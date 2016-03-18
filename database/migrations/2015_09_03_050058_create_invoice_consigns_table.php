<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceConsignsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_consigns', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('number', 10);
			$table->integer('num')->unsigned()->default(0);
			$table->integer('agent_id')->unsigned();
			$table->date('issue_date');
			$table->date('due_date');
			$table->integer('edition_id')->unsigned();
			$table->double('adjustment')->default(0);
			$table->timestamps();
            $table->softDeletes();

		
            // Foreign key
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
		Schema::drop('invoice_consigns');
	}

}
