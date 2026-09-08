<?php

namespace App\Http\Requests;

use App\Models\TrainingLocation;
use Illuminate\Foundation\Http\FormRequest;

class TrainingScheduleStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'club_id' => ['required', 'integer', 'exists:clubs,id'],
            'training_location_id' => ['required', 'integer', 'exists:training_locations,id'],
            'day_of_week' => ['required', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'activity_type' => ['required', 'in:sekolah,luar_sekolah'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $trainingLocation = TrainingLocation::query()->find($this->input('training_location_id'));
            $activityType = $this->input('activity_type');

            if ($trainingLocation && $activityType && $trainingLocation->type !== $activityType) {
                $validator->errors()->add('activity_type', 'Jenis aktivitas harus sesuai dengan tipe lokasi latihan yang dipilih.');
            }
        });
    }
}
