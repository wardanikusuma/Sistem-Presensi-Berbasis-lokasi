<?php

namespace Tests\Feature;

use App\Models\Athlete;
use App\Models\Club;
use App\Models\TrainingLocation;
use App\Models\TrainingSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seed_data_uses_required_enum_values(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', ['role' => 'admin']);
        $this->assertTrue(User::where('role', 'athlete')->exists());

        $allowedTrainingLocationTypes = ['sekolah', 'luar_sekolah'];
        $this->assertTrue(
            TrainingLocation::query()->pluck('type')->every(fn (string $type) => in_array($type, $allowedTrainingLocationTypes, true))
        );

        $allowedActivityTypes = ['sekolah', 'luar_sekolah'];
        $this->assertTrue(
            TrainingSchedule::query()->pluck('activity_type')->every(fn (string $type) => in_array($type, $allowedActivityTypes, true))
        );
    }

    public function test_eloquent_relationships_are_configured(): void
    {
        $user = User::factory()->create(['role' => 'athlete']);
        $athlete = Athlete::factory()->create(['user_id' => $user->id]);
        $club = Club::factory()->create();

        $athlete->clubs()->attach($club->id);

        $this->assertNotNull($user->fresh()->athlete);
        $this->assertEquals($user->id, $athlete->fresh()->user->id);
        $this->assertTrue($athlete->fresh()->clubs->contains('id', $club->id));
    }
}
