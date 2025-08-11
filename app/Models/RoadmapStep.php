<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoadmapStep extends Model
{
    use HasFactory;

    protected $fillable = ['roadmap_id', 'title', 'description', 'order'];

    public function roadmap()
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'roadmap_step_courses');
    }
}
