<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DocumentRequest extends FormRequest
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
            'category' => 'required|string|in:report,proposal,minutes,regulation,certificate,other',
            'visibility' => 'required|string|in:public,members,executives,admin',
            'department_id' => 'required|exists:departments,id',
        ];
        
        // Only require file on create, not on update
        if ($this->isMethod('post')) {
            $rules['file'] = 'required|file|max:10240'; // 10MB max
        } else if ($this->hasFile('file')) {
            $rules['file'] = 'file|max:10240'; // 10MB max
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
            'title.required' => 'The document title is required.',
            'category.required' => 'Please select a document category.',
            'category.in' => 'The selected category is invalid.',
            'visibility.required' => 'Please select a visibility level.',
            'visibility.in' => 'The selected visibility level is invalid.',
            'department_id.required' => 'Please select a department.',
            'file.required' => 'Please upload a document file.',
            'file.file' => 'The uploaded file is invalid.',
            'file.max' => 'The file may not be greater than 10MB.',
        ];
    }
}