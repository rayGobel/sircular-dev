<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('deliveries', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('dist_real_det_id')->unsigned();
            $table->string('order_number', 10);
            $table->date('date_issued');
            $table->smallInteger('quota')->unsigned()->default(0);
            $table->smallInteger('consigned')->unsigned()->default(0);
            $table->smallInteger('gratis')->unsigned()->default(0);
            $table->mediumInteger('number')->unsigned()->default(0);
            $table->boolean('in_invoice_consign')->default(0);
            $table->boolean('in_invoice_quota')->default(0);
			$table->timestamps();
            $table->softDeletes();
			
			// Foreign keys
			$table->foreign('dist_real_det_id')
			    ->references('id')
			    ->on('distribution_realization_details');
			    
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('deliveries');
	}

}
