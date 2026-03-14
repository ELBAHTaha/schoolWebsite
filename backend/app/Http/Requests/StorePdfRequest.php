<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePdfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin', 'secretary', 'professor') ?? false;
    }

    public function rules(): array
    {
        return [
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'title' => ['required', 'string', 'max:255'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }
}
