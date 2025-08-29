<?php

namespace App\Http\Controllers;

use App\Models\RoadmapStep;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\RoadmapStepService;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoadmapStepController extends Controller
{
    use ApiResponseTrait;

    protected $roadmapStepService;

    public function __construct(RoadmapStepService $roadmapStepService)
    {
        $this->roadmapStepService = $roadmapStepService;
    }

    public function indexwep($roadmapId)
    {
        try {
            $steps = $this->roadmapStepService->getStepsByRoadmap($roadmapId);
            return view('roadmap_steps.index', compact('steps'));
        } catch (\Exception $e) {
            return redirect()->route('roadmaps.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function createwep($roadmapId)
    {
        return view('roadmap_steps.create', compact('roadmapId'));
    }

    public function storewep(Request $request, $roadmapId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        try {
            $validated['roadmap_id'] = $roadmapId;

            $roadmapStep = $this->roadmapStepService->createStep($validated);

            return redirect()->route('roadmap_steps.index', ['roadmapId' => $roadmapId])
                ->with('success', 'Step created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function showwep($id)
    {
        try {
            $step = $this->roadmapStepService->getStepById($id);
            return view('roadmap_steps.show', compact('step'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('roadmap_steps.index')->with('error', 'Step not found');
        } catch (\Exception $e) {
            return redirect()->route('roadmap_steps.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function editwep($id)
    {
        try {
            $step = $this->roadmapStepService->getStepById($id);
            return view('roadmap_steps.edit', compact('step'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('roadmap_steps.index')->with('error', 'Step not found');
        } catch (\Exception $e) {
            return redirect()->route('roadmap_steps.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function updatewep(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        try {
            $step = $this->roadmapStepService->getStepById($id);
            $this->roadmapStepService->updateStep($step, $validated);

            return redirect()->route('roadmap_steps.index', ['roadmapId' => $step->roadmap_id])
                ->with('success', 'Step updated successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('roadmap_steps.index')->with('error', 'Step not found');
        } catch (\Exception $e) {
            return redirect()->route('roadmap_steps.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function destroywep($id)
    {
        try {
            $step = $this->roadmapStepService->getStepById($id);
            $roadmapId = $step->roadmap_id;

            $this->roadmapStepService->deleteStep($step);

            return redirect()->route('roadmap_steps.index', ['roadmapId' => $roadmapId])
                ->with('success', 'Step deleted successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('roadmap_steps.index')->with('error', 'Step not found');
        } catch (\Exception $e) {
            return redirect()->route('roadmap_steps.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function attachCourseswep(Request $request, $stepId)
    {
        $validated = $request->validate([
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        try {
            $step = $this->roadmapStepService->getStepById($stepId);
            $courseIds = $validated['course_ids'];

            $this->roadmapStepService->attachCourses($step, $courseIds);

            return redirect()->route('roadmap_steps.show', ['id' => $stepId])
                ->with('success', 'Courses attached successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('roadmap_steps.index')->with('error', 'Step not found');
        } catch (\Exception $e) {
            return redirect()->route('roadmap_steps.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }
    public function getStepsByRoadmap($roadmapId)
    {
        try {
            $steps = $this->roadmapStepService->getStepsByRoadmap($roadmapId);
            return response()->json(['data' => $steps], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unexpected error: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request, $roadmapId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        try {
            $validated['roadmap_id'] = $roadmapId;
            $step = $this->roadmapStepService->createStep($validated);
            return response()->json(['message' => 'Step created successfully', 'data' => $step], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unexpected error: ' . $e->getMessage()], 500);
        }
    }

    public function showStep($id)
    {
        try {
            $step = $this->roadmapStepService->getStepById($id);
            return response()->json(['data' => $step], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Step not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unexpected error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        try {
            $step = $this->roadmapStepService->getStepById($id);
            $updatedStep = $this->roadmapStepService->updateStep($step, $validated);
            return response()->json(['message' => 'Step updated successfully', 'data' => $updatedStep], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Step not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unexpected error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $step = $this->roadmapStepService->getStepById($id);
            $this->roadmapStepService->deleteStep($step);
            return response()->json(['message' => 'Step deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Step not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unexpected error: ' . $e->getMessage()], 500);
        }
    }

    public function attachCourses(Request $request, $stepId)
    {
        $validated = $request->validate([
            'course_ids' => 'required|array',
            'course_ids*' => 'exists:courses,id',
        ]);

        try {
            $step = $this->roadmapStepService->getStepById($stepId);
            $this->roadmapStepService->attachCourses($step, $validated['course_ids']);
            return response()->json(['message' => 'Courses attached successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Step not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unexpected error: ' . $e->getMessage()], 500);
        }
    }
}
