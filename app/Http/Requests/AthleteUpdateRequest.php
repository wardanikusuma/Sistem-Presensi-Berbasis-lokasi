<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AthleteUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        $athleteId = $this->route('athlete')?->id;
        $userId = $this->route('athlete')?->user_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$userId],
            'password' => ['nullable', 'string', 'min:8'],
            'nis' => ['required', 'string', 'max:50', 'unique:athletes,nis,'.$athleteId],
            'nisn' => ['nullable', 'string', 'max:50', 'unique:athletes,nisn,'.$athleteId],
            'class' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:active,inactive'],
            'club_ids' => ['nullable', 'array'],
            'club_ids.*' => ['integer', 'exists:clubs,id'],
        ];
    }
}
