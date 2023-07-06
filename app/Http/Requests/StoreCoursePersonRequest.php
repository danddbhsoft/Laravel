<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCoursePersonRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => [
                'exists:App\Models\Course,id',
                'required',
                Rule::unique('course_person')->where(function ($query) {
                    return $query
                        ->where('course_id', $this->input('course_id'))
                        ->where('person_id', $this->input('person_id'));
                }),
            ],
            'person_id' => [
                'exists:App\Models\Person,id',
                'required',
            ],
        ];
    }
}
