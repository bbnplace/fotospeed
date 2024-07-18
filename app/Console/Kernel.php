<?php

namespace App\Console;

use App\Report\Day;
use App\Report\Hour;
use App\Report\Month;
use App\Report\Year;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // Initialize the Hours, Days, Month and Year
        $schedule->call(function (){
            Hour::initialize();
        })->hourly();

        $schedule->call(function (){
            Day::initialize();
        })->daily();

        $schedule->call(function (){
            Month::initialize();
        })->monthly();
        
        $schedule->call(function (){
            Year::initialize();
        })->yearly();
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
