<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'content',
        'user_id',
        'user_details'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users');
    }
}
