<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'folder_name' => ['nullable', 'string', 'max:80', 'regex:/^[A-Za-z0-9 _.-]+$/'],
            'files' => ['array', 'required_without:folder_name', 'min:1'],
            'files.*' => ['file', 'max:5120', 'mimes:jpg,jpeg,png,pdf,docx,zip,rar'],
        ];
    }

    public function messages(): array
    {
        return [
            'files.required' => 'Add files or create a folder.',
            'files.*.max' => 'Each file must be at most 5MB.',
            'files.*.mimes' => 'Supported types: jpg, png, pdf, docx, zip, rar.',
            'folder_name.regex' => 'Folder name can only include letters, numbers, spaces, dots, underscores, and hyphens.',
        ];
    }
}
