<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonRequest extends FormRequest
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
        $person = $this->route('person');
        return [
            'name' => 'required|regex:/^[\p{L}\s.]+$/u',
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('person', 'email')->ignore($person->id),
            ],
            'birthday' => 'required|date|before:today',
            'phone_number' => 'required|regex:/^0[1-9]\d{8}$/',
            'image_link' => 'file|image|max:10000'
        ];
    }
}
