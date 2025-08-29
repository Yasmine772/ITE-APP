<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assignment;

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
