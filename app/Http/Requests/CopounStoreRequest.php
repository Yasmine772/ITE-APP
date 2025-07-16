<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CopounStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'code'=>'required|min:4|max:6|',
            'discount_type'=>'required|string|in:percent,fixed',
            'discount_value'=>'required|numeric|min:0|max:100',
            'usage_limit'=>'nullable|int|min:1|max:100',
            'valid_from'=>'date|nullable',
            'valid_to'=>'date|nullable',
            'min_order_amount'=>'required|numeric|min:0',
        ];
    }
}
