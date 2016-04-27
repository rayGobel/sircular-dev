<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentMagazineTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('agent_magazine', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('agent_id')->unsigned();
            $table->integer('magazine_id')->unsigned();

            //Fkey relationship
            $table->foreign('agent_id')->references('id')->on('agents');
            $table->foreign('magazine_id')->references('id')->on('magazines');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('agent_magazine');
	}

}
