<?php

namespace App\Services;

use App\Models\RoadmapStep;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoadmapStepService
{
    public function getStepsByRoadmap(int $roadmapId)
    {
        return RoadmapStep::with('courses')->where('roadmap_id', $roadmapId)->orderBy('order')->get();
    }

    public function getStepById(int $id): RoadmapStep
    {
        $step = RoadmapStep::with('courses')->findOrFail($id);

        if (!$step) {
            throw new ModelNotFoundException("Roadmap Step not found.");
        }

        return $step;
    }

    public function createStep(array $data): RoadmapStep
    {
        if (!isset($data['order'])) {
            $lastOrder = RoadmapStep::where('roadmap_id', $data['roadmap_id'])->max('order');
            $data['order'] = $lastOrder ? $lastOrder + 1 : 1;
        }

        return RoadmapStep::create($data);
    }

    public function updateStep(RoadmapStep $step, array $data): RoadmapStep
    {
        $step->update($data);
        return $step;
    }

    public function deleteStep(RoadmapStep $step): bool
    {
        $step->courses()->detach(); 
        return $step->delete();
    }

    public function attachCourses(RoadmapStep $step, array $courseIds)
    {
        return $step->courses()->sync($courseIds);
    }
}
