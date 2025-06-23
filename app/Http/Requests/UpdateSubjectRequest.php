<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateSubjectRequest extends FormRequest
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
    public function rules(): array
    {
          $subjectId = $this->route('subject')?->id ?? $this->route('id');
        return [
             'name' => [
                'required', 'string', 'max:255',
                Rule::unique('subjects')->ignore($subjectId)->where(function ($query) {
                    return $query->where('type', $this->type)
                        ->where('specialization_id', $this->specialization_id)
                        ->where('year_id', $this->year_id);
                }),
            ],
            'type' => 'required|in:theoretical,practical,project',
            'year_id' => 'required|exists:years,id',
            'specialization_id' => 'nullable|exists:specializations,id',
            'semester_id' => 'required|exists:semesters,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ];
    }
}
