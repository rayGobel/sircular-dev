<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('editions', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('magazine_id')->unsigned();
            $table->string('edition_code', 100)->nullable();
            $table->string('main_article', 255)->nullable();
            $table->string('cover', 255)->nullable();
            $table->mediumInteger('price')->unsigned()->default(0);
			$table->timestamps();

            // Foreign keys
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
		Schema::drop('editions');
	}

}
