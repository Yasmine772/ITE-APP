<?php
namespace App\Http\Controllers;

use App\Models\Semester;
use App\Traits\ApiResponseTrait;
use App\Http\Requests\SemesterRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SemesterController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $semesters = Semester::all();

        if ($semesters->isEmpty()) {
            return $this->errorResponse('No semesters found', null, 200);
        }

        return $this->successResponse($semesters, 'Semesters retrieved successfully');
    }

    public function show(Semester $semester)
    {
        return $this->successResponse($semester, 'Semester retrieved successfully');
    }

    public function store(SemesterRequest $request)
    {
        try {
            $semester = Semester::create($request->validated());
            return $this->successResponse($semester, 'Semester created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error during creation', $e->getMessage(), 500);
        }
    }

    public function update(SemesterRequest $request, Semester $semester)
    {
        try {
            $semester->update($request->validated());
            return $this->successResponse($semester, 'Semester updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error during update', $e->getMessage(), 500);
        }
    }

    public function destroy(Semester $semester)
    {
        try {
            $semester->delete();
            return $this->successResponse(null, 'Semester deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error during deletion', $e->getMessage(), 500);
        }
    }

    public function indexView()
    {
        $semesters = Semester::latest()->get();
        return view('semesters.index', compact('semesters'));
    }

    public function createView()
    {
        return view('semesters.create');
    }

    public function storeView(SemesterRequest $request)
    {
        Semester::create($request->validated());
        return redirect()->route('semesters.index')->with('success', 'Semester created successfully');
    }

    public function editView(Semester $semester)
    {
        return view('semesters.edit', compact('semester'));
    }

    public function updateView(SemesterRequest $request, Semester $semester)
    {
        $semester->update($request->validated());
        return redirect()->route('semesters.index')->with('success', 'Semester updated successfully');
    }

    public function destroyView(Semester $semester)
    {
        $semester->delete();
        return redirect()->route('semesters.index')->with('success', 'Semester deleted successfully');
    }
}
