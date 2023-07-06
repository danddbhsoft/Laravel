<?php

namespace App\Http\Requests;

use App\Rules\CustomComparisonRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
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
    public function is_true_time($attribute, $value, $parameters, $validator){

    }
    public function rules(): array
    {
        $course = $this->route('course');
        $old_time = Carbon::createFromFormat('d-m-Y', $course->start_time)->format('d-m-Y');
        return [
            'name' => 'required',
            'description' => 'required',
            'start_time' => [
                'required',
                'date',
                new CustomComparisonRule($old_time),
            ],
            'end_time' => [
                'required',
                'date',
                'after:start_time',
            ],
        ];
    }
}
