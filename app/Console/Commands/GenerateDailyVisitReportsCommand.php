<?php

namespace App\Console\Commands;

use App\Includes\Helper;
use Illuminate\Console\Command;

class GenerateDailyVisitReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:dr {tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates daily visit reports';

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
        Helper::generateDailyVisitReports($this->argument('tenant'));
    }
}
