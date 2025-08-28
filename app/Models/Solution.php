<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    protected $fillable = [
        'title',
        'solutionFile',
        'teacher_id',
        'assignment_id',
        'teacher_details'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
