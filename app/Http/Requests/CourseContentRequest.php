<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseContentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
  public function rules()
{
    return [
        'course_id' => ['nullable', 'exists:courses,id'],
        'title' => ['nullable', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'video_path' => ['nullable', 'file', 'mimes:mp4,avi,mov', 'max:51200'], //  50MB
        'order' => ['nullable', 'integer'],
        'duration' => ['nullable', 'integer'],
        'attachment' => ['nullable', 'file', 'mimes:pdf', 'max:10240'], //  10MB
    ];
}

}
