<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateScammerProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('scammerProfile'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'phone_numbers' => ['sometimes', 'json'],
            'social_handles' => ['sometimes', 'json'],
            'bank_identifiers' => ['sometimes', 'json'],
            'risk_level' => ['sometimes', Rule::in(['low', 'medium', 'high', 'critical'])],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'phone_numbers.json' => 'Phone numbers must be in valid JSON format.',
            'social_handles.json' => 'Social handles must be in valid JSON format.',
            'bank_identifiers.json' => 'Bank identifiers must be in valid JSON format.',
            'risk_level.in' => 'Risk level must be one of: low, medium, high, or critical.',
        ];
    }
}
