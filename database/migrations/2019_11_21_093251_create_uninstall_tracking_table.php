<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUninstallTrackingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('uninstall_tracking', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('reason_id');
			$table->string('plugin');
			$table->string('url');
			$table->string('user_email');
			$table->string('user_name');
			$table->text('reason_info')->nullable();
			$table->text('server_info');
			$table->string('locale');
			$table->string('multisite');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('uninstall_tracking');
	}

}
