<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionRealizationDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('distribution_realization_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('distribution_realization_id')->unsigned();
			$table->integer('agent_id')->unsigned();
			$table->mediumInteger('quota')->unsigned()->default(0);
			$table->mediumInteger('consigned')->unsigned()->default(0);
			$table->mediumInteger('gratis')->unsigned()->default(0);
			$table->timestamps();
            $table->softDeletes();
			
			// Foreign keys
			$table->foreign('distribution_realization_id')
			    ->references('id')
			    ->on('distribution_realizations');
			$table->foreign('agent_id')
			    ->references('id')
			    ->on('agents');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('distribution_realization_details');
	}

}
