<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePluginTrackingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugin_tracking_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tracking_id');
            $table->foreign('tracking_id')->references('id')->on('plugin_tracking');
            $table->text('log');
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
        Schema::dropIfExists('plugin_tracking_details');
    }
}
