<?php
namespace App\Traits;
use App\Models\Resource;


trait SubjectsAndCoursesMorphTrait
{
    public function resources()
    {
        return $this->morphMany(Resource::class,'resourceable');
    }
}
