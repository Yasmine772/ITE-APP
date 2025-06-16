<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question_text',
        'photo',
        'mark',
        'exam_id',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
    public function options()
    {
        return $this->hasMany(Option::class);
    }
    public function answer()
    {
        return $this->hasMany(Answer::class);
    }
}
