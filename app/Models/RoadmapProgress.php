<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoadmapProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'roadmap_id',
        'completed_steps',
        'total_steps',
        'progress_percentage',
        'completed',
        'completed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roadmap()
    {
        return $this->belongsTo(Roadmap::class);
    }
}
