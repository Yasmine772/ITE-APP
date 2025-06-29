<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalBlog extends Model
{
    protected $fillable = [
        'title',
        'body',
    ];
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
