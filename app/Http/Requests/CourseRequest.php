<?php
// app/Http/Requests/CourseRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_free' => 'required|boolean',
            'price' => 'required_if:is_free,false|numeric|min:0',
            'currency_code' => 'required|string|size:3',
            'cover_image' => 'nullable|image|max:2048',
            'duration' => 'nullable|integer|min:0',
            'teacher_id' => 'required|exists:teachers,id',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'nullable|exists:subjects,id',
        ];
    }
}
