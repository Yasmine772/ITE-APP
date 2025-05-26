<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advice extends Model
{
    protected $fillable = [
        'content',
        'teacher_id',
        'subject_id'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subjects');
    }
}
