<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceQuotaDetailDeliveriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_quota_detail_deliveries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('invoice_quota_id')->unsigned();
			$table->integer('delivery_id')->unsigned();
			$table->double('vat')->default(0);
			$table->double('discount')->default(0);
			$table->double('total')->default(0);
			$table->integer('edition_id')->unsigned();
			$table->timestamps();
            $table->softDeletes();
			
			// Foreign keys
			$table->foreign('invoice_quota_id')
			    ->references('id')
			    ->on('invoice_quotas');
			$table->foreign('delivery_id')
			    ->references('id')
			    ->on('deliveries');
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
		Schema::drop('invoice_quota_detail_deliveries');
	}

}
