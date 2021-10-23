<?php

namespace App\Console;

use App\Models\Tenant;
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
        Commands\CalculateUsersDailyMaintenanceCostCommand::class,
        Commands\CalculateUsersDailySmsCostCommand::class,
        Commands\GenerateDailyVisitReportsCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        foreach(Tenant::all() as $tenant){
            // TODO make them daily
            $schedule->command("calculate:maintenance {$tenant->id}")->everyMinute();
            $schedule->command("calculate:sms {$tenant->id}")->everyMinute();
            $schedule->command("generate:dr {$tenant->id}")->everyMinute();
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
