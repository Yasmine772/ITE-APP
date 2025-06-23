<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class CourseService
{
    public function getAllCourses()
    {
        return Course::with(['teacher.user', 'category', 'subject'])->latest()->get();
    }

    public function getCourseById($id)
    {
        $course = Course::with(['teacher.user', 'category', 'subject'])->find($id);

        if (!$course) {
            throw new ModelNotFoundException("Course not found");
        }

        return $course;
    }

    public function createCourse(array $data)
    {
        if (isset($data['cover_image'])) {
            $data['cover_image'] = $data['cover_image']->store('course_covers', 'public');
        }

        return Course::create($data);
    }

    public function updateCourse(Course $course, array $data)
    {
        if (isset($data['cover_image'])) {
            if ($course->cover_image && Storage::disk('public')->exists($course->cover_image)) {
                Storage::disk('public')->delete($course->cover_image);
            }

            $data['cover_image'] = $data['cover_image']->store('course_covers', 'public');
        }

        $course->update($data);

        return $course;
    }

    public function deleteCourse(Course $course)
    {
        if ($course->cover_image && Storage::disk('public')->exists($course->cover_image)) {
            Storage::disk('public')->delete($course->cover_image);
        }

        return $course->delete();
    }

    public function filterCourses(array $filters)
    {
        $query = Course::query();

        if (isset($filters['title']) && $filters['title'] !== '') {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (isset($filters['teacher_id']) && $filters['teacher_id'] !== '') {
            $query->where('teacher_id', $filters['teacher_id']);
        }

        if (isset($filters['category_id']) && $filters['category_id'] !== '') {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['subject_id']) && $filters['subject_id'] !== '') {
            $query->where('subject_id', $filters['subject_id']);
        }

        if (isset($filters['is_free'])) {
            $query->where('is_free', $filters['is_free']);
        }

        $relations = ['teacher.user', 'category', 'subject'];

        if (!empty($filters['with_reviews']) && $filters['with_reviews'] == 'true') {
            $relations[] = 'reviews';
        }

        return $query->with($relations)->get();
    }

    public function getTopRatedCourses($limit = 10)
    {
        return Course::with(['teacher.user', 'category', 'subject'])
                    ->orderByDesc('average_rating')
                    ->limit($limit)
                    ->get();
    }
}
