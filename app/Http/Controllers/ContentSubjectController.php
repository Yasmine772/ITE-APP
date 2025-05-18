<?php

namespace App\Http\Controllers;

use App\Models\ContentSubject;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\ContentSubjectService;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContentSubjectController extends Controller
{
    use ApiResponseTrait;

    protected $contentSubjectService;

    public function __construct(ContentSubjectService $contentSubjectService)
    {
        $this->contentSubjectService = $contentSubjectService;
    }

    public function index()
    {
        $contentSubjects = $this->contentSubjectService->getAllContentSubjects();
        return view('content_subjects.index', compact('contentSubjects'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('content_subjects.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'file' => 'required|file|mimes:pdf|max:10240',
                'lecture_name' => 'required|string|max:255',
                'lecture_order' => 'required|integer|min:1',
            ]);

            $contentSubject = $this->contentSubjectService->createContentSubject($validated);

            return redirect()->route('content_subjects.index')->with('success', 'Content added successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }
    

    public function edit($id)
    {
        try {
            $contentSubject = $this->contentSubjectService->getContentSubjectById($id);
            $subjects = Subject::all();

            return view('content_subjects.edit', compact('contentSubject', 'subjects'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('content_subjects.index')->with('error', 'Content not found');
        } catch (\Exception $e) {
            return redirect()->route('content_subjects.index')->with('error', 'Unexpected error occurred: ' . $e->getMessage());
        }
    }
    public function search(Request $request)
{
    try {
        $validated = $request->validate([
            'lecture_name' => 'nullable|string|max:255',
            'subject_id' => 'nullable|exists:subjects,id',
            'lecture_order' => 'nullable|integer|min:1',
        ]);

        $filters = $validated;
        $contentSubjects = $this->contentSubjectService->filterContentSubjects($filters);

        return view('content_subjects.index', compact('contentSubjects'));
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        return redirect()->route('content_subjects.index')->with('error', 'Unexpected error: ' . $e->getMessage());
    }
}


    public function update(Request $request, $id)
    {
        try {
            $contentSubject = $this->contentSubjectService->getContentSubjectById($id);

            $validated = $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'file' => 'nullable|file|mimes:pdf|max:10240',
                'lecture_name' => 'required|string|max:255',
                'lecture_order' => 'required|integer|min:1',
            ]);

            $updatedContentSubject = $this->contentSubjectService->updateContentSubject($contentSubject, $validated);

            return redirect()->route('content_subjects.index')->with('success', 'Content updated successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('content_subjects.index')->with('error', 'Content not found');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->route('content_subjects.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $contentSubject = $this->contentSubjectService->getContentSubjectById($id);
            $this->contentSubjectService->deleteContentSubject($contentSubject);

            return redirect()->route('content_subjects.index')->with('success', 'Content deleted successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('content_subjects.index')->with('error', 'Content not found');
        } catch (\Exception $e) {
            return redirect()->route('content_subjects.index')->with('error', 'Unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function apiIndex()
    {
        $contentSubjects = $this->contentSubjectService->getAllContentSubjects();
        return $this->successResponse($contentSubjects, 'Content subjects retrieved successfully');
    }

    public function apiShow($id)
    {
        try {
            $contentSubject = $this->contentSubjectService->getContentSubjectById($id);
            return $this->successResponse($contentSubject, 'Content subject retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Content not found', null, 404);
        }
    }

    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'file' => 'required|file|mimes:pdf|max:10240',
                'lecture_name' => 'required|string|max:255',
                'lecture_order' => 'required|integer|min:1',
            ]);

            $contentSubject = $this->contentSubjectService->createContentSubject($validated);

            return $this->successResponse($contentSubject, 'Content created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        try {
            $contentSubject = $this->contentSubjectService->getContentSubjectById($id);

            $validated = $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'file' => 'nullable|file|mimes:pdf|max:10240',
                'lecture_name' => 'required|string|max:255',
                'lecture_order' => 'required|integer|min:1',
            ]);

            $updatedContentSubject = $this->contentSubjectService->updateContentSubject($contentSubject, $validated);
            return $this->successResponse($updatedContentSubject, 'Content updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Content not found', null, 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
        }
    }

    public function apiDestroy($id)
    {
        try {
            $contentSubject = $this->contentSubjectService->getContentSubjectById($id);
            $this->contentSubjectService->deleteContentSubject($contentSubject);
            return $this->successResponse(null, 'Content deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Content not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
        }
    }
    public function apiSearch(Request $request)
{
    try {
        $validated = $request->validate([
            'lecture_name' => 'nullable|string|max:255',
            'subject_id' => 'nullable|exists:subjects,id',
            'lecture_order' => 'nullable|integer|min:1',
        ]);

        $filters = $validated;
        $contentSubjects = $this->contentSubjectService->filterContentSubjects($filters);

        return $this->successResponse($contentSubjects, 'Content subjects retrieved successfully');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return $this->errorResponse('Validation error', $e->errors(), 422);
    } catch (\Exception $e) {
        return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
    }
}

}
