<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agents', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name', 255);
            $table->string('city', 255);
            $table->integer('agent_category_id')->unsigned()->default(0);
            $table->string('phone', 100)->nullable();
            $table->string('contact', 255)->nullable();
            $table->float('discount', 0); // Maximum 1 minimum 0
            $table->float('saving', 0); // Maximum 1 minimum 0
            $table->float('bonus', 0); // Maximum 1 minimum 0
			$table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('agent_category_id')->references('id')->on('agent_categories');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('agents');
	}

}
