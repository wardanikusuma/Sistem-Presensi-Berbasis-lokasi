<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AthleteStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
            'nis' => ['required', 'string', 'max:50', 'unique:athletes,nis'],
            'nisn' => ['nullable', 'string', 'max:50', 'unique:athletes,nisn'],
            'class' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:active,inactive'],
            'club_ids' => ['nullable', 'array'],
            'club_ids.*' => ['integer', 'exists:clubs,id'],
        ];
    }
}
