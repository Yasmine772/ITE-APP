<?php

namespace App\Services;

use App\Models\Subject;
use Illuminate\Validation\ValidationException;

class SubjectService
{

    public function getAllSubjects()
    {
        return Subject::with(['year', 'specialization', 'semester', 'teacher'])->get();
    }


    public function getSubjectById($id)
    {
        return Subject::with(['year', 'specialization', 'semester', 'teacher'])->findOrFail($id);
    }


    public function createSubject(array $data)
    {
        return Subject::create($data);
    }


    public function updateSubject(Subject $subject, array $data)
    {
        $subject->update($data);
        return $subject;
    }


    public function deleteSubject(Subject $subject)
    {
        return $subject->delete();
    }
    public function filterSubjects($filters)
{
    $query = Subject::query();

    if (!empty($filters['name'])) {
        $query->where('name', 'LIKE', '%' . $filters['name'] . '%');
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

    $relations = ['year', 'specialization', 'teacher'];

    if (!empty($filters['with_content']) && $filters['with_content'] == 'true') {
        $relations[] = 'contentSubjects';
    }

    return $query->with($relations)->get();
}

}
