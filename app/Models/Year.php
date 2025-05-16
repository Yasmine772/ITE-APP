<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $fillable=['name'];

  public function specializations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
  {
        return $this->belongsToMany(Specialization::class, 'specialization_year');
    }

}
