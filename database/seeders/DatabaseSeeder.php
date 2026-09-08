<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\Club;
use App\Models\TrainingLocation;
use App\Models\TrainingSchedule;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@sekolah.test'],
            [
                'name' => 'Admin Sekolah',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $athleteUsers = User::query()->where('role', 'athlete')->get();

        if ($athleteUsers->count() < 5) {
            $missingAthleteCount = 5 - $athleteUsers->count();
            $athleteUsers = User::factory($missingAthleteCount)->create([
                'role' => 'athlete',
            ]);
        }

        $clubs = Club::factory(2)->create();

        foreach ($athleteUsers as $index => $user) {
            $athlete = Athlete::factory()->create([
                'user_id' => $user->id,
                'nis' => sprintf('NIS%04d', $index + 1),
                'nisn' => sprintf('NISN%010d', $index + 1),
                'class' => ['X IPA 1', 'XI IPS 2', 'XII TKJ 1'][$index % 3],
                'status' => 'active',
            ]);

            $athlete->clubs()->attach($clubs->random()->id);
        }

        $locations = [
            TrainingLocation::factory()->create([
                'name' => 'Lapangan Sekolah',
                'address' => 'Jl. Pendidikan No. 10',
                'latitude' => -6.200000,
                'longitude' => 106.816666,
                'radius' => 100,
                'type' => 'sekolah',
                'status' => 'active',
            ]),
            TrainingLocation::factory()->create([
                'name' => 'GOR ABC',
                'address' => 'Jl. Atlet No. 10',
                'latitude' => -6.210000,
                'longitude' => 106.826666,
                'radius' => 150,
                'type' => 'luar_sekolah',
                'status' => 'active',
            ]),
        ];

        $schedules = [];
        foreach ($clubs as $clubIndex => $club) {
            foreach (['monday', 'wednesday'] as $day) {
                $schedules[] = TrainingSchedule::factory()->create([
                    'club_id' => $club->id,
                    'training_location_id' => $locations[$clubIndex % 2]->id,
                    'day_of_week' => $day,
                    'start_time' => '16:00:00',
                    'end_time' => '18:00:00',
                    'activity_type' => $clubIndex % 2 === 0 ? 'sekolah' : 'luar_sekolah',
                    'status' => 'active',
                ]);
            }
        }

        foreach ($schedules as $schedule) {
            TrainingSession::factory()->create([
                'training_schedule_id' => $schedule->id,
                'date' => now()->startOfWeek()->addDays(rand(0, 6))->toDateString(),
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'status' => 'scheduled',
            ]);

            TrainingSession::factory()->create([
                'training_schedule_id' => $schedule->id,
                'date' => now()->startOfWeek()->addDays(rand(0, 6))->toDateString(),
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'status' => 'scheduled',
            ]);
        }
    }
}
