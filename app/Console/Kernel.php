<?php

namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // notify user
        $schedule->command('app:appointment:notify')->everyFiveMinutes();
        // clear the expired appointment
        $schedule->command('app:appointment:clear')->daily();
        // delete the dynamically generated SMS or Email template
        $schedule->command('app:template:delete')->weekly();

        if( env('CHECK_SCHEDULER') ){ // log the scheduler is running
            $schedule->command('app:test:scheduler')->everyMinute();
        }
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
