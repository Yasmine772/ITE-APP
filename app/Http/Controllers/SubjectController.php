<?php


namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Subject;
use App\Models\Year;
use App\Models\Specialization;
use App\Models\Semester;
use App\Models\Teacher;
use App\Services\SubjectService;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    use ApiResponseTrait;

    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    /**
     * Handle index for web views
     */
    public function index()
    {
        try {
            $subjects = $this->subjectService->getAllSubjects();
            return view('subjects.index', compact('subjects'));
        } catch (\Exception $e) {
            return $this->handleExceptionForWeb($e);
        }
    }

    /**
     * Handle index for API
     */
    public function apiIndex()
    {
        try {
            $subjects = $this->subjectService->getAllSubjects();
            return $this->successResponse($subjects, 'Subjects retrieved successfully');
        } catch (\Exception $e) {
            return $this->handleExceptionForApi($e);
        }
    }

    /**
     * Handle create for web views
     */
    public function create()
    {
        try {
            $years = Year::all();
            $specializations = Specialization::all();
            $semesters = Semester::all();
            $teachers = Teacher::all();

            return view('subjects.create', compact('years', 'specializations', 'semesters', 'teachers'));
        } catch (\Exception $e) {
            return $this->handleExceptionForWeb($e);
        }
    }

    /**
     * Handle store for web views
     */
    public function store(StoreSubjectRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->subjectService->createSubject($validated);

            return redirect()->route('subjects.index')->with('success', 'Subject created successfully');
        } catch (ValidationException $e) {
            return $this->handleValidationExceptionForWeb($e);
        } catch (\Exception $e) {
            return $this->handleExceptionForWeb($e);
        }
    }

    /**
     * Handle store for API
     */
    public function apiStore(StoreSubjectRequest $request)
    {
        try {
            $subject = $this->subjectService->createSubject($request->validated());
            return $this->successResponse($subject, 'Subject created successfully', 201);
        } catch (ValidationException $e) {
            return $this->handleValidationExceptionForApi($e);
        } catch (\Exception $e) {
            return $this->handleExceptionForApi($e);
        }
    }

    /**
     * Handle edit for web views
     */
    public function edit(Subject $subject)
    {
        try {
            $years = Year::all();
            $specializations = Specialization::all();
            $semesters = Semester::all();
            $teachers = Teacher::all();

            return view('subjects.edit', compact('subject', 'years', 'specializations', 'semesters', 'teachers'));
        } catch (ModelNotFoundException $e) {
            return $this->handleExceptionForWeb($e, 'Subject not found', 404);
        } catch (\Exception $e) {
            return $this->handleExceptionForWeb($e);
        }
    }

    /**
     * Handle update for web views
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        try {
            $validated = $request->validated();
            $this->subjectService->updateSubject($subject, $validated);

            return redirect()->route('subjects.index')->with('success', 'Subject updated successfully');
        } catch (ValidationException $e) {
            return $this->handleValidationExceptionForWeb($e);
        } catch (ModelNotFoundException $e) {
            return $this->handleExceptionForWeb($e, 'Subject not found', 404);
        } catch (\Exception $e) {
            return $this->handleExceptionForWeb($e);
        }
    }

    /**
     * Handle update for API
     */
    public function apiUpdate(UpdateSubjectRequest $request, Subject $subject)
    {
        try {
            $updatedSubject = $this->subjectService->updateSubject($subject, $request->validated());
            return $this->successResponse($updatedSubject, 'Subject updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->handleExceptionForApi($e, 'Subject not found', 404);
        } catch (ValidationException $e) {
            return $this->handleValidationExceptionForApi($e);
        } catch (\Exception $e) {
            return $this->handleExceptionForApi($e);
        }
    }

    /**
     * Handle delete for web views
     */
    public function destroy(Subject $subject)
    {
        try {
            $this->subjectService->deleteSubject($subject);

            return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->handleExceptionForWeb($e, 'Subject not found', 404);
        } catch (\Exception $e) {
            return $this->handleExceptionForWeb($e);
        }
    }

    /**
     * Handle delete for API
     */
    public function apiDestroy(Subject $subject)
    {
        try {
            $this->subjectService->deleteSubject($subject);
            return $this->successResponse(null, 'Subject deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->handleExceptionForApi($e, 'Subject not found', 404);
        } catch (\Exception $e) {
            return $this->handleExceptionForApi($e);
        }
    }

    // ** Handle validation exceptions for web views **
    private function handleValidationExceptionForWeb(ValidationException $e)
    {
        return redirect()->back()->withErrors($e->errors())->withInput();
    }

    // ** Handle general exceptions for web views **
    private function handleExceptionForWeb(\Exception $e, $message = 'Unexpected error occurred', $statusCode = 500)
    {
        return redirect()->route('subjects.index')->with('error', $message . ': ' . $e->getMessage());
    }

    // ** Handle validation exceptions for API **
    private function handleValidationExceptionForApi(ValidationException $e)
    {
        return $this->errorResponse('Validation error', $e->errors(), 422);
    }

    // ** Handle general exceptions for API **
    private function handleExceptionForApi(\Exception $e, $message = 'Unexpected error occurred', $statusCode = 500)
    {
        return $this->errorResponse($message, $e->getMessage(), $statusCode);
    }

    
public function getSubjectsForCurrentTeacher()
{
    try {
        $subjects = $this->subjectService->getSubjectsForCurrentTeacher();

        return view('subjects.index', compact('subjects'));
    } catch (\Exception $e) {
        return $this->handleExceptionForWeb($e, 'Unable to fetch your subjects');
    }
}
public function apigetSubjectsForCurrentTeacher()
{
    try {
        $subjects = $this->subjectService->getSubjectsForCurrentTeacher();

        return $this->successResponse($subjects, 'Subjects retrieved successfully');
    } catch (\Exception $e) {
        return $this->handleExceptionForApi($e, 'Unable to fetch your subjects');
    }
}
public function filter(Request $request)
{
    try {
        $filters = $request->only(['name', 'type', 'year_id', 'specialization_id', 'teacher_id']);
        $subjects = $this->subjectService->filterSubjects($filters);
        return view('subjects.index', compact('subjects'));
    } catch (\Exception $e) {
        return $this->handleExceptionForWeb($e, 'Unable to filter subjects');
    }
}
public function apiFilter(Request $request)
{
    try {
        $filters = $request->only(['name', 'type', 'year_id', 'specialization_id', 'teacher_id']);
        Log::info('Received filters:', $filters);
        $subjects = $this->subjectService->filterSubjects($filters);
        return $this->successResponse($subjects, 'Subjects filtered successfully');
    } catch (\Exception $e) {
        return $this->handleExceptionForApi($e, 'Unable to filter subjects');
    }
}
public function show($id)
{
    try {
        $subject = $this->subjectService->getSubjectById($id);
        return view('subjects.show', compact('subject'));
    } catch (ModelNotFoundException $e) {
        return $this->handleExceptionForWeb($e, 'Subject not found', 404);
    } catch (\Exception $e) {
        return $this->handleExceptionForWeb($e);
    }
}
public function apiShow($id)
{
    try {
        $subject = $this->subjectService->getSubjectById($id);
        return $this->successResponse($subject, 'Subject retrieved successfully');
    } catch (ModelNotFoundException $e) {
        return $this->handleExceptionForApi($e, 'Subject not found', 404);
    } catch (\Exception $e) {
        return $this->handleExceptionForApi($e);
    }
}

}
