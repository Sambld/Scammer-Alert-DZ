<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('report'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'required', 'exists:scam_categories,id'],
            'platform_id' => ['nullable', 'exists:platforms,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255', 'min:10'],
            'description' => ['sometimes', 'required', 'string', 'min:50', 'max:5000'],
            'scammer_name' => ['nullable', 'string', 'max:255'],
            'scammer_phone' => ['nullable', 'string', 'max:255'],
            'scammer_social_handle' => ['nullable', 'string', 'max:255'],
            'scammer_profile_url' => ['nullable', 'url', 'max:500'],
            'scammer_bank_identifier' => ['nullable', 'string', 'max:100'],
            'incident_date' => ['nullable', 'date', 'before_or_equal:today'],
            'media.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:10240'], // 10MB max
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'scam category',
            'platform_id' => 'platform',
            'scammer_phone' => 'scammer phone number',
            'scammer_social_handle' => 'scammer social media handle',
            'scammer_profile_url' => 'scammer profile URL',
            'scammer_bank_identifier' => 'scammer bank identifier',
            'incident_date' => 'incident date',
            'media.*' => 'media file',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.min' => 'The title must be at least 10 characters.',
            'description.min' => 'The description must be at least 50 characters to provide sufficient detail.',
            'description.max' => 'The description cannot exceed 5000 characters.',
            'incident_date.before_or_equal' => 'The incident date cannot be in the future.',
            'media.*.mimes' => 'Only JPG, PNG, PDF, DOC, and DOCX files are allowed.',
            'media.*.max' => 'Each media file cannot exceed 10MB.',
        ];
    }
}
