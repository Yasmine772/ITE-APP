<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $fillable = [
        'user_id',
        'exam_id',
        'start_time',
        'end_time',
        'status',
        'due_mark',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users');
    }
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exams');
    }
}
