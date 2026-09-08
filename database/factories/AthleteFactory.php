<?php

namespace Database\Factories;

use App\Models\Athlete;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Athlete>
 */
class AthleteFactory extends Factory
{
    protected $model = Athlete::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'athlete'])->id,
            'nis' => fake()->unique()->numerify('########'),
            'nisn' => fake()->unique()->numerify('##########'),
            'class' => fake()->randomElement(['X IPA 1', 'XI IPS 2', 'XII TKJ 1']),
            'phone' => fake()->phoneNumber(),
            'status' => 'active',
        ];
    }
}
