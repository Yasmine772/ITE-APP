<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
  use SoftDeletes ;

  protected $fillable = [
        'title',
        'cover_image',
         'file' ,
         'teacher_id',
         'resourceable_id',
         'resourceable_type'];

  public function resourceable(): MorphTo
  {
    return $this->morphTo();
  }
  public function teacher(): BelongsTo
  {
      return $this->belongsTo(Teacher::class);
  }
  public function user(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
  {
      return $this->belongsToMany(User::class);
  }
}
