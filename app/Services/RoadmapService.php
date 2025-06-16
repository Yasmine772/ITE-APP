<?php

namespace App\Services;

use App\Models\Roadmap;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoadmapService
{
    public function getAllRoadmaps()
    {
        return Roadmap::with('steps')->latest()->get();
    }

    public function getRoadmapById(int $id): Roadmap
    {
        $roadmap = Roadmap::with('steps.courses')->findOrFail($id);

        if (!$roadmap) {
            throw new ModelNotFoundException("Roadmap not found.");
        }

        return $roadmap;
    }

    public function createRoadmap(array $data): Roadmap
    {
        return Roadmap::create($data);
    }

    public function updateRoadmap(Roadmap $roadmap, array $data): Roadmap
    {
        $roadmap->update($data);
        return $roadmap;
    }

    public function deleteRoadmap(Roadmap $roadmap): bool
    {
        return $roadmap->delete();
    }
}
