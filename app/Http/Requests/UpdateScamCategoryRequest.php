<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateScamCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('scamCategory'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categoryId = $this->route('scamCategory')->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('scam_categories')->ignore($categoryId)],
            'name_ar' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('scam_categories')->ignore($categoryId)],
            'name_fr' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('scam_categories')->ignore($categoryId)],
            'description' => ['nullable', 'string', 'max:1000'],
            'description_ar' => ['nullable', 'string', 'max:1000'],
            'description_fr' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'category name (English)',
            'name_ar' => 'category name (Arabic)',
            'name_fr' => 'category name (French)',
            'description' => 'description (English)',
            'description_ar' => 'description (Arabic)',
            'description_fr' => 'description (French)',
            'is_active' => 'active status',
        ];
    }
}
