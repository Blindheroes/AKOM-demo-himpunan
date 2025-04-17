<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EventRequest extends FormRequest
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
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'organizer_id' => 'nullable|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'max_participants' => 'nullable|integer|min:0',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'is_featured' => 'sometimes|boolean',
            'budget' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048', // 2MB max
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
            'title.required' => 'The event title is required.',
            'start_date.required' => 'The event start date is required.',
            'start_date.after_or_equal' => 'The event start date must be today or in the future.',
            'end_date.required' => 'The event end date is required.',
            'end_date.after_or_equal' => 'The event end date must be after or equal to the start date.',
            'department_id.required' => 'Please select a department for this event.',
            'image.image' => 'The file must be an image.',
            'image.max' => 'The image size must not exceed 2MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert is_featured checkbox value to boolean
        if ($this->has('is_featured')) {
            $this->merge([
                'is_featured' => filter_var($this->is_featured, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
