<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $fillable=['name'];
    public function years()
    {
        return $this->belongsToMany(Year::class, 'specialization_year');
    }


}
