<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeletePluginTrackerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:plugin-tracker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Plugin Tracker Data After One Month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * Delete Delete Plugin Tracker Data After One Month.
     *
     * @return null
     */
    public function handle()
    {
        DB::delete("delete from `plugin_tracking_details` where `created_at` < '".date('Y-m-d')."' - INTERVAL 30  DAY");
        DB::delete("delete from `plugin_tracking` where `created_at` < '".date('Y-m-d')."' - INTERVAL 30  DAY");
        Log::info("Tracking Data Delete Successfully!");
    }
}
