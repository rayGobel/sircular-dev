<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionPlanDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('distribution_plan_details', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('distribution_plan_id')->unsigned();
            $table->integer('agent_id')->unsigned();
            $table->mediumInteger('quota')->unsigned()->default(0);
            $table->mediumInteger('consigned')->unsigned()->default(0);
            $table->mediumInteger('gratis')->unsigned()->default(0);
			$table->timestamps();

            // Foreign keys
            $table->foreign('distribution_plan_id')
                ->references('id')
                ->on('distribution_plans');
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
		Schema::drop('distribution_plan_details');
	}

}
