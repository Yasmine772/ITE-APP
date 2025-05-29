<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

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
    ];


    public function teacher()
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
}
