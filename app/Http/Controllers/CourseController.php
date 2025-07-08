<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Services\CourseService;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\CourseRequest;

class CourseController extends Controller
{
    use ApiResponseTrait;

    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index()
    {
        $courses = $this->courseService->getAllCourses();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = Category::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        return view('courses.create', compact('categories', 'subjects', 'teachers'));
    }

    public function store(CourseRequest $request)
    {
        try {
            $user = $request->user();

            $validated = $request->validated();

            $validated['teacher_id'] = $user->teacher->id;

            $this->courseService->createCourse($validated);

            return redirect()->route('courses.index')->with('success', 'Course created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $course = $this->courseService->getCourseById($id);
            $categories = Category::all();
            $subjects = Subject::all();
            $teachers = Teacher::all();

            return view('courses.edit', compact('course', 'categories', 'subjects', 'teachers'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('courses.index')->with('error', 'Course not found');
        } catch (\Exception $e) {
            return redirect()->route('courses.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function update(CourseRequest $request, $id)
    {
        try {
            $course = $this->courseService->getCourseById($id);

            $validated = $request->validated();

            $this->courseService->updateCourse($course, $validated);

            return redirect()->route('courses.index')->with('success', 'Course updated successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('courses.index')->with('error', 'Course not found');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->route('courses.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $course = $this->courseService->getCourseById($id);
            $this->courseService->deleteCourse($course);

            return redirect()->route('courses.index')->with('success', 'Course deleted successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('courses.index')->with('error', 'Course not found');
        } catch (\Exception $e) {
            return redirect()->route('courses.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function filter(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'category_id' => 'nullable|exists:categories,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'is_free' => 'nullable|boolean',
        ]);

        $filters = $validated;

        $courses = $this->courseService->filterCourses($filters);

        $courses->transform(function ($course) {
            $course->teacher_name = $course->teacher && $course->teacher->user
                ? $course->teacher->user->name
                : null;

            return $course;
        });

        return view('courses.index', compact('courses'));
    }

    public function show($id)
    {
        try {
            $course = $this->courseService->getCourseById($id);

            $course->load(['teacher.user']);

            return view('courses.show', compact('course'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('courses.index')->with('error', 'Course not found');
        } catch (\Exception $e) {
            return redirect()->route('courses.index')->with('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function apiIndex()
    {
        $courses = $this->courseService->getAllCourses();
        return $this->successResponse($courses, 'Courses retrieved successfully');
    }

    public function apiShow($id)
    {
        try {
            $course = $this->courseService->getCourseById($id);
            $course->load(['teacher.user']);

            $course->teacher_name = optional($course->teacher->user)->name;
            unset($course->teacher);

            return $this->successResponse($course, 'Course retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Course not found', null, 404);
        }
    }

    public function apiStore(CourseRequest $request)
    {
        try {
            $user = $request->user();

            $validated = $request->validated();

            $validated['teacher_id'] = $user->teacher->id;

            $course = $this->courseService->createCourse($validated);
            return $this->successResponse($course, 'Course created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
        }
    }

    public function apiUpdate(CourseRequest $request, $id)
    {
        try {
            $course = $this->courseService->getCourseById($id);

            $validated = $request->validated();

            $updated = $this->courseService->updateCourse($course, $validated);
            return $this->successResponse($updated, 'Course updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Course not found', null, 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
        }
    }

    public function apiFilter(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'teacher_id' => 'nullable|exists:teachers,id',
                'category_id' => 'nullable|exists:categories,id',
                'subject_id' => 'nullable|exists:subjects,id',
                'is_free' => 'nullable|boolean',
            ]);

            $filters = $validated;
            $courses = $this->courseService->filterCourses($filters);
            $courses->transform(function ($course) {
                $course->teacher_name = $course->teacher && $course->teacher->user ? $course->teacher->user->name : null;

                unset($course->teacher);

                return $course;
            });

            if ($courses->isEmpty()) {
                return $this->successResponse([], 'No results match your search criteria');
            }

            return $this->successResponse($courses, 'Filtered courses retrieved successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
        }
    }

    public function apiDestroy($id)
    {
        try {
            $course = $this->courseService->getCourseById($id);
            $this->courseService->deleteCourse($course);
            return $this->successResponse(null, 'Course deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Course not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error', $e->getMessage(), 500);
        }
    }

    public function topRated()
    {
        $courses = $this->courseService->getTopRatedCourses();

        return view('courses.top-rated', compact('courses'));
    }

    public function topRatedCourses()
    {
        try {
            $courses = $this->courseService->getTopRatedCourses(10);
            return response()->json($courses);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }
 public function apiMyCourses(Request $request)
{
    try {
        $user = $request->user();

        if (!$user->teacher) {
            return $this->errorResponse('You are not associated with any teacher profile.', null, 403);
        }

        $courses = $user->teacher->courses;

        return $this->successResponse($courses, 'Courses retrieved successfully.');
    } catch (\Exception $e) {
        return $this->errorResponse('Unexpected error occurred.', $e->getMessage(), 500);
    }
}
public function myCourses(Request $request)
{
    try {
        $user = $request->user();

        if (!$user->teacher) {
            return redirect()->route('courses.index')->with('error', 'You are not associated with any teacher profile.');
        }

        $courses = $user->teacher->courses;

        return view('courses.my-courses', compact('courses'));
    } catch (\Exception $e) {
        return redirect()->route('courses.index')->with('error', 'Unexpected error: ' . $e->getMessage());
    }
}



}
