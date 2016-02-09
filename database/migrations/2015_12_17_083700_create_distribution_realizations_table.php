<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionRealizationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('distribution_realizations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('distribution_plan_id')->unsigned();
			$table->integer('edition_id')->unsigned();
			$table->mediumInteger('print')->unsigned()->default(0);
			$table->mediumInteger('gratis')->unsigned()->default(0);
			$table->mediumInteger('distributed')->unsigned()->default(0);
			$table->mediumInteger('stock')->unsigned()->default(0);
			$table->date('date');
			$table->smallInteger('print_number')->unsigned()->default(1);
			$table->timestamps();
			
			// Foreign keys
			$table->foreign('distribution_plan_id')
			    ->references('id')
			    ->on('distribution_plans');
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
		Schema::drop('distribution_realizations');
	}

}
