<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Year;
use App\Models\Specialization;
use App\Models\Semester;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Services\SubjectService;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    use ApiResponseTrait;

    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function index()
    {
        $subjects = $this->subjectService->getAllSubjects();
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        $years = Year::all();
        $specializations = Specialization::all();
        $semesters = Semester::all();
        $teachers = Teacher::all();

        return view('subjects.create', compact('years', 'specializations', 'semesters', 'teachers'));
    }

   public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects')->where(function ($query) use ($request) {
                    return $query->where('type', $request->type)
                                 ->where('specialization_id', $request->specialization_id)
                                 ->where('year_id', $request->year_id);
                }),
            ],
            'type' => 'required|in:theoretical,practical,project',
            'year_id' => 'required|exists:years,id',
            'specialization_id' => 'nullable|exists:specializations,id',
            'semester_id' => 'required|exists:semesters,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $this->subjectService->createSubject($validated);

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Unexpected error: ' . $e->getMessage());
    }
}

    public function edit($id)
    {
        try {
            $subject = $this->subjectService->getSubjectById($id);
            $years = Year::all();
            $specializations = Specialization::all();
            $semesters = Semester::all();
            $teachers = Teacher::all();

            return view('subjects.edit', compact('subject', 'years', 'specializations', 'semesters', 'teachers'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('subjects.index')->with('error', 'Subject not found');
        } catch (\Exception $e) {
            return redirect()->route('subjects.index')->with('error', 'Unexpected error occurred: ' . $e->getMessage());
        }
    }

    
    public function update(Request $request, $id)
{
    try {
        $subject = $this->subjectService->getSubjectById($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('type', $request->type)
                                 ->where('specialization_id', $request->specialization_id)
                                 ->where('year_id', $request->year_id);
                }),
            ],
            'type' => 'required|in:theoretical,practical,project',
            'year_id' => 'required|exists:years,id',
            'specialization_id' => 'nullable|exists:specializations,id',
            'semester_id' => 'required|exists:semesters,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $this->subjectService->updateSubject($subject, $validated);

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully');
    } catch (ModelNotFoundException $e) {
        return redirect()->route('subjects.index')->with('error', 'Subject not found');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        return redirect()->route('subjects.index')->with('error', 'Unexpected error: ' . $e->getMessage());
    }
}
public function search(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'type' => 'nullable|in:theoretical,practical,project',
            'year_id' => 'nullable|exists:years,id',
            'specialization_id' => 'nullable|exists:specializations,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'with_content' => 'nullable|boolean',
        ]);

        $filters = $validated;
        $subjects = $this->subjectService->filterSubjects($filters);

        return view('subjects.index', compact('subjects'));

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        return redirect()->route('subjects.index')->with('error', 'Unexpected error: ' . $e->getMessage());
    }
}


    public function destroy($id)
    {
        try {
            $subject = $this->subjectService->getSubjectById($id);
            $this->subjectService->deleteSubject($subject);

            return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('subjects.index')->with('error', 'Subject not found');
        } catch (\Exception $e) {
            return redirect()->route('subjects.index')->with('error', 'Unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function apiIndex()
    {
        $subjects = $this->subjectService->getAllSubjects();
        return $this->successResponse($subjects, 'Subjects retrieved successfully');
    }

    public function apiShow($id)
    {
        try {
            $subject = $this->subjectService->getSubjectById($id);
            return $this->successResponse($subject, 'Subject retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Subject not found', null, 404);
        }
    }

    public function apiStore(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects')->where(function ($query) use ($request) {
                    return $query->where('type', $request->type)
                                 ->where('specialization_id', $request->specialization_id)
                                 ->where('year_id', $request->year_id);
                }),
            ],
            'type' => 'required|in:theoretical,practical,project',
            'year_id' => 'required|exists:years,id',
            'specialization_id' => 'nullable|exists:specializations,id',
            'semester_id' => 'required|exists:semesters,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $subject = $this->subjectService->createSubject($validated);
        return $this->successResponse($subject, 'Subject created successfully', 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return $this->errorResponse('Validation error', $e->errors(), 422);
    } catch (\Exception $e) {
        return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
    }
}


   public function apiUpdate(Request $request, $id)
{
    try {
        $subject = $this->subjectService->getSubjectById($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('type', $request->type)
                                 ->where('specialization_id', $request->specialization_id)
                                 ->where('year_id', $request->year_id);
                }),
            ],
            'type' => 'required|in:theoretical,practical,project',
            'year_id' => 'required|exists:years,id',
            'specialization_id' => 'nullable|exists:specializations,id',
            'semester_id' => 'required|exists:semesters,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $updatedSubject = $this->subjectService->updateSubject($subject, $validated);
        return $this->successResponse($updatedSubject, 'Subject updated successfully');
    } catch (ModelNotFoundException $e) {
        return $this->errorResponse('Subject not found', null, 404);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return $this->errorResponse('Validation error', $e->errors(), 422);
    } catch (\Exception $e) {
        return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
    }
}

public function apiSearch(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'type' => 'nullable|in:theoretical,practical,project',
            'year_id' => 'nullable|exists:years,id',
            'specialization_id' => 'nullable|exists:specializations,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'with_content' => 'nullable|boolean',
        ]);

        $filters = $validated;
        $subjects = $this->subjectService->filterSubjects($filters);

        return $this->successResponse($subjects, 'Subjects retrieved successfully');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return $this->errorResponse('Validation error', $e->errors(), 422);
    } catch (\Exception $e) {
        return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
    }
}

    public function apiDestroy($id)
    {
        try {
            $subject = $this->subjectService->getSubjectById($id);
            $this->subjectService->deleteSubject($subject);
            return $this->successResponse(null, 'Subject deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Subject not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
        }
    }
}
