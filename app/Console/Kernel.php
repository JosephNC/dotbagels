<?php

namespace App\Console;

use App\Jobs\SquareApiJob;
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
        // $schedule->command('inspire')->hourly();
        $schedule->job(new SquareApiJob)
            ->hourly()
            ->withoutOverlapping();

        // Clear logs and caches
        $schedule->call( function() use ($schedule) {
            $schedule->command('clear-compiled');
            $schedule->command('optimize');
            $schedule->command('auth:clear-resets');
            $schedule->command('cache:clear');
            $schedule->command('optimize:clear');
            $schedule->command('view:clear');
            // $schedule->command('logs:clear')->daily();
            $schedule->command('logs:clear')->runInBackground();
        } )->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
