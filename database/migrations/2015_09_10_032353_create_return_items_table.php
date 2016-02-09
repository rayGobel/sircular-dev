<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('return_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('dist_realization_det_id')->unsigned();
			$table->integer('agent_id')->unsigned();
			$table->integer('edition_id')->unsigned();
			$table->integer('magazine_id')->unsigned();
			$table->date('date');
			$table->string('number', 10);
			$table->mediumInteger('num')->unsigned->default(0);
			$table->mediumInteger('total')->unsigned->default(0);
			$table->boolean('in_invoice')->default(0);
			$table->timestamps();
			
			// Foreign key
			$table->foreign('dist_realization_id')
			    ->references('id')
			    ->on('distribution_realization_details');
			$table->foreign('agent_id')
			    ->references('id')
			    ->on('agents');
			$table->foreign('edition_id')
			    ->references('id')
			    ->on('editions');
			$table->foreign('magazine_id')
			    ->references('id')
			    ->on('magazines');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('return_items');
	}

}
