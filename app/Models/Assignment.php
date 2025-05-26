<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'title',
        'file',
        'teacher_id',
        'subject_id',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subjects');
    }

    public function solution()
    {
        return $this->hasMany(Solution::class, 'solutions');
    }




}
