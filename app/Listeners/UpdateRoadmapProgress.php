<?php

namespace App\Listeners;

use App\Events\CourseCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\RoadmapStep;
use App\Services\RoadmapProgressService;


class UpdateRoadmapProgress  implements ShouldQueue 
{
     use InteractsWithQueue;

     protected RoadmapProgressService $progressService;
    /**
     * Create the event listener.
     */
    public function __construct( RoadmapProgressService $progressService)
    {
          $this->progressService = $progressService;
    }

    /**
     * Handle the event.
     */
    public function handle(CourseCompleted $event): void
    {
         $steps = RoadmapStep::whereHas('courses', function ($q) use ($event) {
            $q->where('courses.id', $event->courseId);
        })->get();

        foreach ($steps as $step) {
            $this->progressService->recalculate($event->userId, $step->roadmap_id);
        }
        
    }
}
