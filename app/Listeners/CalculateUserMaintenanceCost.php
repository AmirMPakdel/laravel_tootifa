<?php

namespace App\Listeners;

use App\Events\FileContentCrudHappened;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CalculateUserMaintenanceCost implements ShouldQueue
{
    public function onFileContentCrudHappened($event){
        Log::debug("tenant: " + tenant()->id);
    }

    public function subscribe($events){
        $events->listen(
            'App\Events\FileContentCrudHappened',
            'App\Listeners\CalculateUserMaintenanceCost@onFileContentCrudHappened'
        );
    }
}
