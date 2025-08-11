<?php

namespace App\Services;

use App\Models\CourseContent;
use Illuminate\Support\Facades\Storage;
use App\Models\Course;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;

class CourseContentService
{
    public function getContentsByCourse($courseId)
    {
        return CourseContent::where('course_id', $courseId)->orderBy('order')->get();
    }

    public function createContent(array $data)
    {
        if (!isset($data['order'])) {
            $lastOrder = CourseContent::where('course_id', $data['course_id'])->max('order');
            $data['order'] = $lastOrder ? $lastOrder + 1 : 1;
        }

        if (isset($data['video_path'])) {
            $data['video_path'] = $data['video_path']->store('videos', 'public');
            $videoPath = storage_path('app/public/' . $data['video_path']);

            $ffprobe = FFProbe::create([
                'ffmpeg.binaries'  => 'C:\\Users\\my laptop\\Downloads\\ffmpeg-7.1.1-essentials_build\\ffmpeg-7.1.1-essentials_build\\bin\\ffmpeg.exe',
                'ffprobe.binaries' => 'C:\\Users\\my laptop\\Downloads\\ffmpeg-7.1.1-essentials_build\\ffmpeg-7.1.1-essentials_build\\bin\\ffprobe.exe',
                'timeout' => 3600,
                'ffmpeg.threads' => 4,
            ]);

            $durationInSeconds = $ffprobe->format($videoPath)->get('duration');
            $data['duration'] = $durationInSeconds;
        }

        if (isset($data['attachment'])) {
            $data['attachment'] = $data['attachment']->store('attachments', 'public');
        }

        $courseContent = CourseContent::create($data);

        $this->recalculateCourseDuration($data['course_id']);

        return $courseContent;
    }

    public function updateContent(CourseContent $courseContent, array $data)
    {
        if (isset($data['video_path'])) {
            if ($courseContent->video_path && Storage::disk('public')->exists($courseContent->video_path)) {
                Storage::disk('public')->delete($courseContent->video_path);
            }

            $data['video_path'] = $data['video_path']->store('videos', 'public');
            $videoPath = storage_path('app/public/' . $data['video_path']);

            $ffprobe = FFProbe::create([
                'ffmpeg.binaries'  => 'C:\\Users\\my laptop\\Downloads\\ffmpeg-7.1.1-essentials_build\\ffmpeg-7.1.1-essentials_build\\bin\\ffmpeg.exe',
                'ffprobe.binaries' => 'C:\\Users\\my laptop\\Downloads\\ffmpeg-7.1.1-essentials_build\\ffmpeg-7.1.1-essentials_build\\bin\\ffprobe.exe',
                'timeout' => 3600,
                'ffmpeg.threads' => 4,
            ]);

            $durationInSeconds = $ffprobe->format($videoPath)->get('duration');
            $data['duration'] = $durationInSeconds;
        }

        if (isset($data['attachment'])) {
            if ($courseContent->attachment && Storage::disk('public')->exists($courseContent->attachment)) {
                Storage::disk('public')->delete($courseContent->attachment);
            }
            $data['attachment'] = $data['attachment']->store('attachments', 'public');
        }

        $courseContent->update($data);

        $this->recalculateCourseDuration($courseContent->course_id);

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

        $courseId = $content->course_id;

        $deleted = $content->delete();

        $this->recalculateCourseDuration($courseId);

        return $deleted;
    }

    public function search($courseId, $title = null)
    {
        $query = CourseContent::where('course_id', $courseId)->orderBy('order');

        if (!empty($title)) {
            $query->where('title', 'like', '%' . $title . '%');
        }

        return $query->get();
    }

    protected function recalculateCourseDuration($courseId)
    {
        $totalDuration = CourseContent::where('course_id', $courseId)->sum('duration');
        Course::where('id', $courseId)->update(['duration' => $totalDuration]);
    }
}
