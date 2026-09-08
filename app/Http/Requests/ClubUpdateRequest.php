<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClubUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        $clubId = $this->route('club')?->id;

        return [
            'name' => ['required', 'string', 'max:255', 'unique:clubs,name,'.$clubId],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
