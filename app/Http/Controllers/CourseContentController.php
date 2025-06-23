<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseContentRequest;
use App\Models\CourseContent;
use App\Services\CourseContentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class CourseContentController extends Controller
{
    use ApiResponseTrait;

    protected $courseContentService;

    public function __construct(CourseContentService $courseContentService)
    {
        $this->courseContentService = $courseContentService;
    }

    // API METHODS

    public function index(int $courseId)
    {
        try {
            $contents = $this->courseContentService->getContentsByCourse($courseId);
            return $this->successResponse($contents, 'Course contents retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve contents', $e->getMessage());
        }
    }

    public function store(CourseContentRequest $request)
    {
        try {
            $data = $request->validated();
            $content = $this->courseContentService->createContent($data);
  $durationInSeconds = $content->duration;

        $hours = floor($durationInSeconds / 3600);
        $minutes = floor(($durationInSeconds % 3600) / 60);
        $seconds = floor($durationInSeconds % 60);

        $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);            return $this->successResponse([
                'content' => $content,
                'duration_hms' => $formattedDuration
            ], 'Content created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create content', $e->getMessage());
        }
    }

    public function update(CourseContentRequest $request, CourseContent $content)
    {
        try {
            $data = $request->validated();
            $updatedContent = $this->courseContentService->updateContent($content, $data);
            return $this->successResponse($updatedContent, 'Content updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update content', $e->getMessage());
        }
    }

    public function destroy(CourseContent $content)
    {
        try {
            $this->courseContentService->deleteContent($content);
            return $this->successResponse(null, 'Content deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete content', $e->getMessage());
        }
    }

    public function show(CourseContent $content)
    {
        try {
            return $this->successResponse($content, 'Content retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve content', $e->getMessage());
        }
    }

    public function search(Request $request, int $courseId)
    {
        try {
            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
            ]);
            $title = $validated['title'] ?? null;
            $contents = $this->courseContentService->search($courseId, $title);

            if ($contents->isEmpty()) {
                return $this->successResponse([], 'No course contents match your search criteria');
            }

            return $this->successResponse($contents, 'Course contents retrieved successfully');
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation error', $e->errors(), 422);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Course not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
        }
    }


    // WEB METHODS

    public function webIndex(int $courseId)
    {
        $contents = $this->courseContentService->getContentsByCourse($courseId);
        return view('course_contents.index', compact('contents', 'courseId'));
    }

    public function webShow(CourseContent $content)
    {
        return view('course_contents.show', compact('content'));
    }

    public function webSearch(Request $request, int $courseId)
    {
        try {
            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
            ]);

            $title = $validated['title'] ?? null;
            $contents = $this->courseContentService->search($courseId, $title);

            if ($contents->isEmpty()) {
                return view('course_contents.index', [
                    'contents' => $contents,
                    'courseId' => $courseId,
                    'message' => 'No course contents match your search criteria.'
                ]);
            }

            return view('course_contents.index', compact('contents', 'courseId'));
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Unexpected error: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function webCreate(int $courseId)
    {
        return view('course_contents.create', compact('courseId'));
    }

    public function webStore(CourseContentRequest $request)
    {
        $data = $request->validated();

        try {
            $this->courseContentService->createContent($data);
            return redirect()->route('course_contents.webIndex', $data['course_id'])
                ->with('success', 'Content created successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create content: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function webEdit(CourseContent $content)
    {
        return view('course_contents.edit', compact('content'));
    }

    public function webUpdate(CourseContentRequest $request, CourseContent $content)
    {
        $data = $request->validated();

        try {
            $this->courseContentService->updateContent($content, $data);
            return redirect()->route('course_contents.webIndex', $content->course_id)
                ->with('success', 'Content updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update content: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function webDestroy(CourseContent $content)
    {
        try {
            $courseId = $content->course_id;
            $this->courseContentService->deleteContent($content);
            return redirect()->route('course_contents.webIndex', $courseId)
                ->with('success', 'Content deleted successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete content: ' . $e->getMessage()]);
        }
    }
    public function downloadVideo(CourseContent $content)
    {
        if (!$content->video_path || !Storage::disk('public')->exists($content->video_path)) {
            return abort(404, 'Video file not found.');
        }

        return Storage::disk('public')->download($content->video_path);
    }

    public function downloadAttachment(CourseContent $content)
    {
        if (!$content->attachment || !Storage::disk('public')->exists($content->attachment)) {
            return abort(404, 'Attachment file not found.');
        }

        return Storage::disk('public')->download($content->attachment);
    }
}
