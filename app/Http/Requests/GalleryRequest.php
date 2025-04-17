<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GalleryRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_id' => 'nullable|exists:events,id',
            'is_featured' => 'sometimes|boolean',
        ];

        // Only validate images on creation, not on update
        if ($this->isMethod('post')) {
            $rules['images'] = 'required|array';
            $rules['images.*'] = 'image|max:5120'; // 5MB max per image
            $rules['captions'] = 'nullable|array';
            $rules['captions.*'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The gallery title is required.',
            'images.required' => 'You must upload at least one image.',
            'images.*.image' => 'The uploaded files must be images.',
            'images.*.max' => 'Each image may not be greater than 5MB.',
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
