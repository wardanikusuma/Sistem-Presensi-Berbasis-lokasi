<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AthleteAttendanceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAthlete() === true;
    }

    public function rules(): array
    {
        return [
            'training_session_id' => ['required', 'integer', 'exists:training_sessions,id'],
            'training_location_id' => ['nullable', 'integer', 'exists:training_locations,id'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
