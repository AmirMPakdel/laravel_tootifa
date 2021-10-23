<?php

namespace App\Console\Commands;

use App\Includes\Helper;
use Illuminate\Console\Command;

class CalculateUsersDailyMaintenanceCostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:maintenance {tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Calculates Users' Daily Maintenance Cost and saves report";

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
     *
     * @return int
     */
    public function handle()
    {
        Helper::calculateUsersDailyMaintenanceCost($this->argument('tenant'));
    }
}
