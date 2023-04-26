<?php

namespace App\Console;

use App\Console\Commands\SendNotiNotEvent;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SendMailAuto;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        SendMailAuto::class,
        SendNotiNotEvent::class,
        SendMailAuto::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('epoint:connection_string')
            ->everyThirtyMinutes();

//        $schedule->command('epoint:run-url')
//            ->everyFiveMinutes();
//
        $schedule->command('epoint:run-url 1')
            ->monthlyOn(1, '00:00');
//
        $schedule->command('epoint:run-url-every-day')
            ->daily();

        $schedule->call('epoint:check-kpi-note-status')
            ->daily();
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
