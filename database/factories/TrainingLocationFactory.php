<?php

namespace Database\Factories;

use App\Models\TrainingLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TrainingLocation>
 */
class TrainingLocationFactory extends Factory
{
    protected $model = TrainingLocation::class;

    public function definition(): array
    {
        return [
            'name' => 'GOR '.fake()->company(),
            'address' => fake()->address(),
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'radius' => 100,
            'type' => fake()->randomElement(['sekolah', 'luar_sekolah']),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
