<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ResourceRequest extends FormRequest
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
            'title'=>'required|string|max:255',
            'file'=>'required|file|mimes:pdf,doc,docx|max:10240',
            'resourceable_type' => 'required|string|in:course,subject',
            'resourceable_name' => 'required|string',
              ];
}

    }

