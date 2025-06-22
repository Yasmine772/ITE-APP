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

    public function index($roadmapId)
    {
        try {
            $steps = $this->roadmapStepService->getStepsByRoadmap($roadmapId);
            return view('roadmap_steps.index', compact('steps'));
        } catch (\Exception $e) {
            return redirect()->route('roadmaps.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function create($roadmapId)
    {
        return view('roadmap_steps.create', compact('roadmapId'));
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

            $roadmapStep = $this->roadmapStepService->createStep($validated);

            return redirect()->route('roadmap_steps.index', ['roadmapId' => $roadmapId])
                ->with('success', 'Step created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function show($id)
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

    public function edit($id)
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

    public function update(Request $request, $id)
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

    public function destroy($id)
    {
        try {
            $step = $this->roadmapStepService->getStepById($id);
            $roadmapId = $step->roadmap_id; // حفظ الـ roadmapId قبل الحذف

            $this->roadmapStepService->deleteStep($step);

            return redirect()->route('roadmap_steps.index', ['roadmapId' => $roadmapId])
                ->with('success', 'Step deleted successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('roadmap_steps.index')->with('error', 'Step not found');
        } catch (\Exception $e) {
            return redirect()->route('roadmap_steps.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function attachCourses(Request $request, $stepId)
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
}
