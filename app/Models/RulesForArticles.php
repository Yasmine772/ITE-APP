<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RulesForArticles extends Model
{
    protected $fillable = ['rule_section','rejection_reason'];
}
