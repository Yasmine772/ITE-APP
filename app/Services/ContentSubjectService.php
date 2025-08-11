<?php

namespace App\Services;

use App\Models\ContentSubject;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContentSubjectService
{
   
    public function getAllContentSubjects()
    {
        return ContentSubject::with('subject')->get();
    }

   
    public function getContentSubjectById($id)
    {
        $contentSubject = ContentSubject::find($id);

        if (!$contentSubject) {
            throw new ModelNotFoundException('Content subject not found');
        }

        return $contentSubject;
    }

   
    public function createContentSubject(array $data)
    {
        $filePath = $data['file']->store('content_subjects', 'public');
        return ContentSubject::create([
            'subject_id' => $data['subject_id'],
            'file_path' => $filePath,
            'lecture_name' => $data['lecture_name'],
            'lecture_order' => $data['lecture_order'],
        ]);
    }

   
  public function updateContentSubject(ContentSubject $contentSubject, array $data)
{
    if (isset($data['file'])) {
        if ($contentSubject->file_path && Storage::disk('public')->exists($contentSubject->file_path)) {
            Storage::disk('public')->delete($contentSubject->file_path);
        }
        $contentSubject->file_path = $data['file']->store('content_subjects', 'public');
    }

    $contentSubject->update([
        'subject_id' => $data['subject_id'],
        'lecture_name' => $data['lecture_name'],
        'lecture_order' => $data['lecture_order'],
        'file_path' => $contentSubject->file_path,
    ]);

    return $contentSubject;
}



    
    public function deleteContentSubject(ContentSubject $contentSubject)
    {

        if ($contentSubject->file_path) {
            Storage::delete($contentSubject->file_path);
        }

        $contentSubject->delete();
    }
    public function filterContentSubjects($filters)
    {
        $query = ContentSubject::query();

        if (!empty($filters['lecture_name'])) {
            $query->where('lecture_name', 'LIKE', '%' . $filters['lecture_name'] . '%');
        }

        if (!empty($filters['subject_id'])) {
            $query->where('subject_id', $filters['subject_id']);
        }

        if (!empty($filters['lecture_order'])) {
            $query->where('lecture_order', $filters['lecture_order']);
        }

        return $query->with('subject')->get();
    }
}
