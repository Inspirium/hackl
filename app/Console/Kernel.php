<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tennis:ranks')->hourly();
        $schedule->command('tennis:verify')->hourly();
        $schedule->command('tennis:notifications')->daily();
        $schedule->command('tennis:updateElo')->everyMinute()->withoutOverlapping();
        $schedule->command('auth:clear-resets')->daily();
        $schedule->command('passport:purge')->daily();
        $schedule->command('queue:flush')->daily();
        $schedule->command('clean:clubs')->hourly();
        $schedule->command('tennis:score-games-in-leagues')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('app:score-competition')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('app:fix-club-in-games')->daily()->withoutOverlapping();
        $schedule->command('clear:unpaid-reservations')->everyMinute()->withoutOverlapping();
        $schedule->command('app:create-subscription-invoices first')->monthlyOn(1, '07:00');
        $schedule->command('app:create-subscription-invoices middle')->monthlyOn(15, '07:00');
        $schedule->command('app:create-subscription-invoices last')->lastDayOfMonth('07:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
