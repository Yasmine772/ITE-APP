<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseContentProgress extends Model
{
        protected $fillable = [
        'user_id',
        'course_content_id',
        'is_completed',
        'completed_at',
        'last_watched_position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function courseContent()
    {
        return $this->belongsTo(CourseContent::class);
    }

}
