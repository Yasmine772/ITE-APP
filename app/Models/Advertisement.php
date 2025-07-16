<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $table = 'advertisements';
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'file',
        'image'
    ];
   public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
   {
       return $this->belongsTo('App\Models\User');
   }
    public function recipients()
    {
        return $this->belongsToMany(User::class, 'advertisement_user')->withTimestamps();
    }


}
