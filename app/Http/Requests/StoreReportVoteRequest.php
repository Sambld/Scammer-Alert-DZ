<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportVoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if user can vote on this specific report
        return $this->user()->can('voteOnReport', [$this->route('report')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vote_type' => ['required', 'in:upvote,downvote'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'vote_type.required' => 'Please specify a vote type.',
            'vote_type.in' => 'Invalid vote type. Must be either upvote or downvote.',
        ];
    }
}
