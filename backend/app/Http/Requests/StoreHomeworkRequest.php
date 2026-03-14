<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHomeworkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('professor', 'admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
        ];
    }
}
