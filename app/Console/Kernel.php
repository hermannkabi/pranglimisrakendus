<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('auth:clear-resets')->everyFifteenMinutes();

        // See tuleks ümber teha
        // Streak peaks ju uuenema kohe, kui kasutaja mängib, mitte ainult keskööl
        // Streak tuleks aga kesköösel lõpetada küll
        $schedule->call(function () {
            app('App\Http\Controllers\ProfileController')->checkStreak();
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
