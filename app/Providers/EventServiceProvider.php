<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\CourseCompleted::class => [
            \App\Listeners\UpdateRoadmapProgress::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
