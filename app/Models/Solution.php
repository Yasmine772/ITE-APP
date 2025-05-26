<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    protected $fillable = [
        'solutionFile',
        'user_id',
        'assignment_id',
        'user_details'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
