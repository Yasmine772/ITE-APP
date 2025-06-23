<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'completed',
        'progress_percentage',
        'last_accessed_at',
         'completed' => 'boolean',
        'progress_percentage' => 'integer',
        'last_accessed_at' => 'datetime',
    ];


    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
