<?php
namespace App\Services;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;

class SubjectService
{
    public function getAllSubjects(): Collection
    {
        return Subject::with(['year', 'specialization', 'semester', 'teacher'])->get();
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

        if (!empty($filters['teacher_id'])) {
            $query->where('teacher_id', $filters['teacher_id']);
        }

 

        return $query->get();
    }
}
