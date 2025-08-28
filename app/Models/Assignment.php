<?php

namespace App\Models;

use Attribute;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'title',
        'file',
        'teacher_id',
        'subject_id',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function solution()
    {
        return $this->hasOne(Solution::class);
    }

    protected function fileUrl(): Attribute
    { 
        return Attribute::make(
            get: fn() => $this->file ? Storage::disk('public')->url($this->file) : '#',
        );
    }




}
