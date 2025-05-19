<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Year extends Model
{
    protected $fillable = ['name'];

    public function specializations(): BelongsToMany
    {
        return $this->belongsToMany(Specialization::class, 'specialization_year');
    }
      public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
