<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin', 'directeur', 'secretary') ?? false;
    }

    public function rules(): array
    {
        $allowedRoles = [
            'admin',
            'directeur',
            'secretary',
            'professor',
            'student',
            'visitor',
            'commercial',
        ];

        if (!$this->user()?->hasRole('admin')) {
            $allowedRoles = ['professor', 'student', 'visitor'];
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in($allowedRoles)],
            'phone' => ['nullable', 'string', 'max:30'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['integer', 'exists:classes,id'],
            'payment_status' => ['nullable', 'in:paid,pending,late'],
            'working_hours' => ['nullable', 'array'],
            'working_hours.*.day' => ['nullable', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'working_hours.*.starts_at' => ['nullable', 'date_format:H:i'],
            'working_hours.*.ends_at' => ['nullable', 'date_format:H:i'],
        ];
    }
}
