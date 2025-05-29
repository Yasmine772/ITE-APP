<?php

namespace App\Services;

use App\Models\CourseContent;
use Illuminate\Support\Facades\Storage;

class CourseContentService
{
    public function getContentsByCourse($courseId)
    {
        $x = CourseContent::where('course_id', $courseId)->orderBy('order')->get();
        return $x;
    }
    public function createContent(array $data)
    {
        if (!isset($data['order'])) {
        $lastOrder = CourseContent::where('course_id', $data['course_id'])->max('order');
        $data['order'] = $lastOrder ? $lastOrder + 1 : 1;
    }
        if (isset($data['video_path'])) {
            $data['video_path'] = $data['video_path']->store('videos', 'public');
        }

        if (isset($data['attachment'])) {
            $data['attachment'] = $data['attachment']->store('attachments', 'public');
        }
        $coursecontents = CourseContent::create($data);
        return $coursecontents;
    }
    public function updateContent(CourseContent $courseContent, array $data)
    {
        if (isset($data['video_path'])) {
            if ($courseContent->video_path && Storage::disk('public')->exists($courseContent->video_path)) {
                Storage::disk('public')->delete('$courseContent->video_path');
            }
            $data['video_path'] = $data['video_path']->store('videos', 'public');
        }
        if (isset($data['attachment'])) {
            if ($courseContent->attachment && Storage::disk('public')->exists($courseContent->attachment)) {
                Storage::disk('public')->delete($courseContent->attachment);
            }
            $data['attachment'] = $data['attachment']->store('attachments', 'public');
        }
        $courseContent->update($data);
        return $courseContent;
    }
    public function deleteContent(CourseContent $content)
    {
        if ($content->video_path && Storage::disk('public')->exists($content->video_path)) {
            Storage::disk('public')->delete($content->video_path);
        }

        if ($content->attachment && Storage::disk('public')->exists($content->attachment)) {
            Storage::disk('public')->delete($content->attachment);
        }

        return $content->delete();
    }
    public function search($courseId, $title = null)
{
     $query = CourseContent::where('course_id', $courseId)->orderBy('order');

    if (!empty($title)) {
        $query->where('title', 'like', '%' . $title . '%');
    }

    return $query->get();
}
}