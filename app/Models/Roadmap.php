<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Roadmap extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    public function steps()
    {
        return $this->hasMany(RoadmapStep::class)->orderBy('order');
    }

    public function progress()
    {
        return $this->hasMany(RoadmapProgress::class);
    }
}
