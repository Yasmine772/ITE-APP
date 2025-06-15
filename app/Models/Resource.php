<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['name','cover_image','pdf_file'];
     public function courses()
     {
       return $this->belongsTo(Course::class);
     }
     public function subjects()
     {
         return $this->belongsToMany(Subject::class);
     }
}
