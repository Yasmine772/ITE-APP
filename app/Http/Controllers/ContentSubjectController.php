<?php

namespace App\Http\Controllers;

use App\Models\ContentSubject;
use App\Models\Subject;
use Illuminate\Http\Request;
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
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'required|file|mimes:pdf|max:10240',
            'lecture_name' => 'required|string|max:255',
            'lecture_order' => 'required|integer|min:1',
        ]);

        $subject = Subject::findOrFail($validated['subject_id']);
        $this->authorize('create', [ContentSubject::class, $subject]);

        $this->contentSubjectService->createContentSubject($validated);

        return redirect()->route('content_subjects.index')->with('success', 'Content added successfully');
    }

    public function edit($id)
    {
        $contentSubject = $this->contentSubjectService->getContentSubjectById($id);
        $this->authorize('update', $contentSubject);

        $subjects = Subject::all();
        return view('content_subjects.edit', compact('contentSubject', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        $contentSubject = $this->contentSubjectService->getContentSubjectById($id);
        $this->authorize('update', $contentSubject);

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'nullable|file|mimes:pdf|max:10240',
            'lecture_name' => 'required|string|max:255',
            'lecture_order' => 'required|integer|min:1',
        ]);

        $this->contentSubjectService->updateContentSubject($contentSubject, $validated);

        return redirect()->route('content_subjects.index')->with('success', 'Content updated successfully');
    }

    public function destroy($id)
    {
        $contentSubject = $this->contentSubjectService->getContentSubjectById($id);
        $this->authorize('delete', $contentSubject);

        $this->contentSubjectService->deleteContentSubject($contentSubject);

        return redirect()->route('content_subjects.index')->with('success', 'Content deleted successfully');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'lecture_name' => 'nullable|string|max:255',
            'subject_id' => 'nullable|exists:subjects,id',
            'lecture_order' => 'nullable|integer|min:1',
        ]);

        $contentSubjects = $this->contentSubjectService->filterContentSubjects($validated);

        return view('content_subjects.index', compact('contentSubjects'));
    }

    public function apiIndex()
    {
        $contentSubjects = $this->contentSubjectService->getAllContentSubjects();
        return $this->successResponse($contentSubjects, 'Content subjects retrieved successfully');
    }

    public function apiShow($id)
    {
        $contentSubject = $this->contentSubjectService->getContentSubjectById($id);
        $this->authorize('view', $contentSubject);

        return $this->successResponse($contentSubject, 'Content subject retrieved successfully');
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'required|file|mimes:pdf|max:10240',
            'lecture_name' => 'required|string|max:255',
            'lecture_order' => 'required|integer|min:1',
        ]);

        $subject = Subject::findOrFail($validated['subject_id']);
        $this->authorize('create', [ContentSubject::class, $subject]);

        $contentSubject = $this->contentSubjectService->createContentSubject($validated);

        return $this->successResponse($contentSubject, 'Content created successfully', 201);
    }

    public function apiUpdate(Request $request, $id)
    {
        $contentSubject = $this->contentSubjectService->getContentSubjectById($id);
        $this->authorize('update', $contentSubject);

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'nullable|file|mimes:pdf|max:10240',
            'lecture_name' => 'required|string|max:255',
            'lecture_order' => 'required|integer|min:1',
        ]);

        $updatedContentSubject = $this->contentSubjectService->updateContentSubject($contentSubject, $validated);

        return $this->successResponse($updatedContentSubject, 'Content updated successfully');
    }

    public function apiDestroy($id)
    {
        $contentSubject = $this->contentSubjectService->getContentSubjectById($id);
        $this->authorize('delete', $contentSubject);

        $this->contentSubjectService->deleteContentSubject($contentSubject);

        return $this->successResponse(null, 'Content deleted successfully');
    }

    public function apiSearch(Request $request)
    {
        $validated = $request->validate([
            'lecture_name' => 'nullable|string|max:255',
            'subject_id' => 'nullable|exists:subjects,id',
            'lecture_order' => 'nullable|integer|min:1',
        ]);

        $contentSubjects = $this->contentSubjectService->filterContentSubjects($validated);

        return $this->successResponse($contentSubjects, 'Content subjects retrieved successfully');
    }
}
