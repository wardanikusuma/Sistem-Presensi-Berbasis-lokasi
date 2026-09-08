<?php

namespace Database\Factories;

use App\Models\TrainingSchedule;
use App\Models\TrainingSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TrainingSession>
 */
class TrainingSessionFactory extends Factory
{
    protected $model = TrainingSession::class;

    public function definition(): array
    {
        return [
            'training_schedule_id' => TrainingSchedule::factory(),
            'date' => now()->toDateString(),
            'start_time' => '16:00:00',
            'end_time' => '18:00:00',
            'status' => 'scheduled',
        ];
    }
}
