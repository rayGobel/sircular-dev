<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagazinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('magazines', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name', 255);
            $table->integer('publisher_id')->unsigned();
            $table->string('period', 100);
            $table->mediumInteger('price')->unsigned()->default(0);
            $table->float('percent_fee')->default(0);
            $table->float('percent_value')->default(0);
			$table->timestamps();

            // Foreign key
            $table->foreign('publisher_id')->references('id')->on('publishers');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('magazines');
	}

}
