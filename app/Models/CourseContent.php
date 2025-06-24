<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'video_path',
        'order',
        'duration',
        'description',
        'attachment',
        'average_rating',
        'duration_hms'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function getAverageRatingAttribute($value)
    {
        return round($this->ratings()->avg('rating') ?? 0.0, 1);
    }
     public function progresses()
    {
        return $this->hasMany(CourseContentProgress::class);
    }
    public function exam()
    {
        return $this->hasMany(Exam::class, 'exams');
    }
}
