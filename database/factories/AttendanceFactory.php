<?php

namespace Database\Factories;

use App\Models\Athlete;
use App\Models\Attendance;
use App\Models\TrainingSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'athlete_id' => Athlete::factory(),
            'training_session_id' => TrainingSession::factory(),
            'attendance_status' => 'hadir',
            'check_in_at' => now(),
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'gps_accuracy' => 8.00,
            'distance_from_location' => 40,
            'location_status' => 'sesuai',
            'note' => null,
        ];
    }
}
