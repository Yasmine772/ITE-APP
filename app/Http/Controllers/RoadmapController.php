<?php

namespace App\Http\Controllers;

use App\Models\Roadmap;
use Illuminate\Http\Request;
use App\Services\RoadmapService;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class RoadmapController extends Controller
{
    use ApiResponseTrait;

    protected $roadmapService;

    public function __construct(RoadmapService $roadmapService)
    {
        $this->roadmapService = $roadmapService;
    }

    public function index()
    {
        $roadmaps = $this->roadmapService->getAllRoadmaps();
        return view('roadmaps.index', compact('roadmaps'));
    }

    public function create()
    {
        return view('roadmaps.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $this->roadmapService->createRoadmap($validated);
            return redirect()->route('roadmaps.index')->with('success', 'Roadmap created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unexpected error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $roadmap = $this->roadmapService->getRoadmapById($id);
            return view('roadmaps.edit', compact('roadmap'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('roadmaps.index')->with('error', 'Roadmap not found');
        } catch (\Exception $e) {
            return redirect()->route('roadmaps.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $roadmap = $this->roadmapService->getRoadmapById($id);
            $this->roadmapService->updateRoadmap($roadmap, $validated);

            return redirect()->route('roadmaps.index')->with('success', 'Roadmap updated successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('roadmaps.index')->with('error', 'Roadmap not found');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->route('roadmaps.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $roadmap = $this->roadmapService->getRoadmapById($id);
            $this->roadmapService->deleteRoadmap($roadmap);

            return redirect()->route('roadmaps.index')->with('success', 'Roadmap deleted successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('roadmaps.index')->with('error', 'Roadmap not found');
        } catch (\Exception $e) {
            return redirect()->route('roadmaps.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $roadmap = $this->roadmapService->getRoadmapById($id);
            return view('roadmaps.show', compact('roadmap'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('roadmaps.index')->with('error', 'Roadmap not found');
        } catch (\Exception $e) {
            return redirect()->route('roadmaps.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }


    public function apiIndex()
    {
        $roadmaps = $this->roadmapService->getAllRoadmaps();
        return $this->successResponse($roadmaps, 'Roadmaps retrieved successfully');
    }

    public function apiShow($id)
    {
        try {
            $roadmap = $this->roadmapService->getRoadmapById($id);
            return $this->successResponse($roadmap, 'Roadmap retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Roadmap not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
        }
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $roadmap = $this->roadmapService->createRoadmap($validated);
            return $this->successResponse($roadmap, 'Roadmap created successfully', 201);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $roadmap = $this->roadmapService->getRoadmapById($id);
            $updated = $this->roadmapService->updateRoadmap($roadmap, $validated);

            return $this->successResponse($updated, 'Roadmap updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Roadmap not found', null, 404);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
        }
    }

    public function apiDestroy($id)
    {
        try {
            $roadmap = $this->roadmapService->getRoadmapById($id);
            $this->roadmapService->deleteRoadmap($roadmap);

            return $this->successResponse(null, 'Roadmap deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Roadmap not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
        }
    }
}
