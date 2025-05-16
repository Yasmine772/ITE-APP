<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $fillable=['name'];
<<<<<<< HEAD
  
  public function specializations()
    {
=======

  public function specializations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
  {
>>>>>>> a74942e7baa9c99995047ffcc5334ae48c910eff
        return $this->belongsToMany(Specialization::class, 'specialization_year');
    }

}
