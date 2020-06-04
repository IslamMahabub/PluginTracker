<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSiteToUninstallTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('uninstall_tracking', function (Blueprint $table) {
            $table->string('site')->nullable();
            $table->string('admin_email')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('hash')->nullable();
            $table->string('version')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uninstall_tracking', function (Blueprint $table) {
            $table->dropColumn('site');
            $table->dropColumn('admin_email');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('hash');
            $table->dropColumn('version');
        });
    }
}
