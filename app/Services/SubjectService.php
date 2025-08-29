<?php
namespace App\Services;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
class SubjectService
{
    public function getAllSubjects(): Collection
    {
        return Subject::with(['year', 'specialization', 'semester', 'teacher'])->get();
    }
    public function getSubjectsForCurrentTeacher()
{
    $teacher = Auth::user()->teacher;

    if (!$teacher) {
        throw new \Exception("User is not a teacher");
    }

 return Subject::where('teacher_id', $teacher->id)
                  ->with(['year', 'semester', 'specialization']) 
                  ->get();
                }

    public function getSubjectById(int $id): Subject
    {
        return Subject::with(['year', 'specialization', 'semester', 'teacher'])->findOrFail($id);
    }

    public function createSubject(array $data): Subject
    {
        return Subject::create($data);
    }

    public function updateSubject(Subject $subject, array $data): Subject
    {
        $subject->update($data);
        return $subject;
    }

    public function deleteSubject(Subject $subject): bool
    {
        return $subject->delete();
    }

    public function filterSubjects(array $filters): Collection
    {
        $query = Subject::query()->with(['year', 'specialization', 'semester', 'teacher']);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['year_id'])) {
            $query->where('year_id', $filters['year_id']);
        }

        if (!empty($filters['specialization_id'])) {
            $query->where('specialization_id', $filters['specialization_id']);
        }
         if (!empty($filters['semester_id'])) {
            $query->where('semester_id', $filters['semester_id']);
        }

        if (!empty($filters['teacher_id'])) {
            $query->where('teacher_id', $filters['teacher_id']);
        }

 

        return $query->get();
    }
}
