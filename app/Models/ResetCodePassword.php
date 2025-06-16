<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetCodePassword extends Model
{
    protected $fillable = [
        'email',
        'code',

    ];
    protected $table ='reset_code_passwords';
}
