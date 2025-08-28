<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration',
        'user_id',
        'subject_id',
        'course_id',
        'content_course_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subjects');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'courses');
    }
    public function content_course()
    {
        return $this->belongsTo(CourseContent::class, 'course_contents');
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function mark()
    {
        return $this->hasMany(Mark::class, 'marks');
    }
}
