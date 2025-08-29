<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\SubjectsAndCoursesMorphTrait;

class Course extends Model
{
    use HasFactory;
    use SubjectsAndCoursesMorphTrait;

    protected $fillable = [
        'title',
        'description',
        'is_free',
        'price',
        'currency_code',
        'cover_image',
        'duration',
        'teacher_id',
        'category_id',
        'subject_id',
        'average_rating',
    ];

    public function teacher(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function contents()
    {
        return $this->hasMany(CourseContent::class)->orderBy('order');
    }

    public function ratings()
    {
        return $this->hasManyThrough(
            Rating::class,
            CourseContent::class,
            'course_id',
            'course_content_id',
            'id',
            'id'
        );
    }

    public function getAverageRatingAttribute($value)
    {
        return round($this->ratings()->avg('rating') ?? 0.0, 1);
    }

    public function subscriptions()
    {
        return $this->hasMany(CourseSubscription::class);
    }

    public function progresses()
    {
        return $this->hasMany(CourseProgress::class);
    }

    public function steps()
    {
        return $this->belongsToMany(RoadmapStep::class, 'roadmap_step_courses');
    }

    public function exam()
    {
        return $this->hasMany(Exam::class, 'exams');
    }

public function purchases()
{
    return $this->hasMany(Purchase::class);
}

public function students()
{
    return $this->belongsToMany(User::class, 'purchases')
                ->withPivot('amount_paid', 'payment_status')
                ->withTimestamps();
}

}
