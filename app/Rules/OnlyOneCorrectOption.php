<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OnlyOneCorrectOption implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
      //  dd(func_get_args()); 
            $correctAnswers = collect($value)
            ->filter(fn($option) => $option['is_correct'] == true )
            ->count();

        if ($correctAnswers !== 1) {
            $fail('You can select only one correct answer');
        }
        
    }
}
