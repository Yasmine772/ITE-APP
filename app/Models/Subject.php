<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\SubjectsAndCoursesMorphTrait;

class Subject extends Model
{
    use HasFactory;
    use SubjectsAndCoursesMorphTrait;

    protected $fillable = [
        'name',
        'type',
        'year_id',
        'specialization_id',
        'semester_id',
        'teacher_id',
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function teacher(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    public function contentSubjects()
    {
        return $this->hasMany(ContentSubject::class);
    }

    public function advice()
    {
        return $this->hasMany(Advice::class);
    }

    public function assignment()
    {
        return $this->hasMany(Assignment::class);
    }


    public function courses()
    {
        return $this->hasMany(Course::class);
    }


    public function exams()
    {
        return $this->hasMany(Exam::class, 'exams');
    }
}
