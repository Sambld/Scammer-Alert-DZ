<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ScamCategory;

class StoreScamCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', ScamCategory::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:scam_categories,name'],
            'name_ar' => ['required', 'string', 'max:255', 'unique:scam_categories,name_ar'],
            'name_fr' => ['required', 'string', 'max:255', 'unique:scam_categories,name_fr'],
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
