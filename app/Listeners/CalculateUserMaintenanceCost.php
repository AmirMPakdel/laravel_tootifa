<?php

namespace App\Listeners;

use App\Events\FileContentCrudHappened;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CalculateUserMaintenanceCost implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(FileContentCrudHappened $event)
    {
        Log::debug("tenant: " . tenant()->id);
    }

}
