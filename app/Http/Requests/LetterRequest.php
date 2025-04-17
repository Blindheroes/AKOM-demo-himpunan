<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LetterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['staff', 'executive', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'template_id' => 'required|exists:letter_templates,id',
            'date' => 'required|date',
            'regarding' => 'required|string|max:255',
            'recipient' => 'required|string|max:255',
            'recipient_position' => 'nullable|string|max:255',
            'recipient_institution' => 'nullable|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The letter title is required.',
            'template_id.required' => 'Please select a letter template.',
            'date.required' => 'The letter date is required.',
            'regarding.required' => 'The subject/regarding field is required.',
            'recipient.required' => 'The recipient name is required.',
            'content.required' => 'The letter content is required.',
            'department_id.required' => 'Please select a department.',
        ];
    }
}
