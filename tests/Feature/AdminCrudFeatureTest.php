<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\TrainingLocation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_club_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/admin/clubs')
            ->assertOk();
    }

    public function test_athlete_cannot_access_admin_routes(): void
    {
        $athlete = User::factory()->create(['role' => 'athlete']);

        $this->actingAs($athlete)
            ->get('/admin/clubs')
            ->assertForbidden();
    }

    public function test_club_name_must_be_unique(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Club::factory()->create(['name' => 'Club A']);

        $this->actingAs($admin)
            ->from('/admin/clubs/create')
            ->post('/admin/clubs', [
                'name' => 'Club A',
                'description' => 'Duplicate',
                'status' => 'active',
            ])
            ->assertSessionHasErrors('name');
    }

    public function test_training_schedule_rejects_activity_and_location_type_mismatch(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $club = Club::factory()->create();
        $location = TrainingLocation::factory()->create(['type' => 'sekolah']);

        $this->actingAs($admin)
            ->from('/admin/training-schedules/create')
            ->post('/admin/training-schedules', [
                'club_id' => $club->id,
                'training_location_id' => $location->id,
                'day_of_week' => 'monday',
                'start_time' => '16:00',
                'end_time' => '18:00',
                'activity_type' => 'luar_sekolah',
                'status' => 'active',
            ])
            ->assertSessionHasErrors('activity_type');
    }
}
