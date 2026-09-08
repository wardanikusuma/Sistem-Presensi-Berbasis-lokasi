<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\TrainingLocation;
use App\Models\TrainingSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TrainingSchedule>
 */
class TrainingScheduleFactory extends Factory
{
    protected $model = TrainingSchedule::class;

    public function definition(): array
    {
        return [
            'club_id' => Club::factory(),
            'training_location_id' => TrainingLocation::factory(),
            'day_of_week' => fake()->randomElement(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']),
            'start_time' => '16:00:00',
            'end_time' => '18:00:00',
            'activity_type' => fake()->randomElement(['sekolah', 'luar_sekolah']),
            'status' => 'active',
        ];
    }
}
