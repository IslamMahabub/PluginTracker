<?php

namespace App\Console;

use App\Console\Commands\DeletePluginTrackerData;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        DeletePluginTrackerData::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('delete:plugin-tracker')
            ->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

// php artisan schedule:run
// Add in Cron job list
//* * * * * php /path/to/artisan schedule:run 1>> /dev/null 2>&1
//* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1