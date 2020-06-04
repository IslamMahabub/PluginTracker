<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePluginTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugin_tracking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('site', 200);
            $table->string('url', 200);
            $table->string('admin_email', 100);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('hash', 100);
            $table->string('plugin', 500);
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
        Schema::dropIfExists('plugin_tracking');
    }
}
