<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionPlansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('distribution_plans', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('edition_id')->unsigned();
            $table->mediumInteger('print')->unsigned()->default(0);
            $table->mediumInteger('gratis')->unsigned()->default(0);
            $table->mediumInteger('distributed')->unsigned()->default(0);
            $table->mediumInteger('stock')->unsigned()->default(0);
            $table->date('publish_date')->nullable();
            $table->smallInteger('print_number')->unsigned->default(1);
            $table->boolean('is_realized')->default(0);
			$table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('edition_id')->references('id')->on('editions');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('distribution_plans');
	}

}
