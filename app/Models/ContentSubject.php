<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'file_path',
        'lecture_name',
        'lecture_order',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}

