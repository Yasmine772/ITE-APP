<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('subjects')->where(function ($query) {
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

    public function authorize(): bool
    {
        return true;
    }
}
