<?php

namespace Tests\Feature;

use App\Models\Athlete;
use App\Models\Club;
use App\Models\TrainingLocation;
use App\Models\TrainingSchedule;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function makeValidSessionForAthlete(?User $user = null): array
    {
        $user ??= User::factory()->create(['role' => 'athlete']);
        $athlete = Athlete::factory()->create(['user_id' => $user->id, 'status' => 'active']);

        $club = Club::factory()->create(['status' => 'active']);
        $athlete->clubs()->attach($club->id);

        $location = TrainingLocation::factory()->create([
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'radius' => 100,
            'status' => 'active',
        ]);

        $schedule = TrainingSchedule::factory()->create([
            'club_id' => $club->id,
            'training_location_id' => $location->id,
            'activity_type' => 'sekolah',
            'status' => 'active',
        ]);

        $session = TrainingSession::factory()->create([
            'training_schedule_id' => $schedule->id,
            'date' => now()->toDateString(),
            'start_time' => now()->copy()->subMinute()->format('H:i:s'),
            'end_time' => now()->copy()->addMinutes(90)->format('H:i:s'),
            'status' => 'active',
        ]);

        return [$user, $athlete, $club, $location, $schedule, $session];
    }

    public function test_athlete_can_open_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'athlete', 'name' => 'Budi Atlet']);
        Athlete::factory()->create(['user_id' => $user->id, 'status' => 'active']);

        $this->actingAs($user)
            ->get('/athlete/dashboard')
            ->assertOk()
            ->assertSee('Budi Atlet');
    }

    public function test_admin_cannot_access_athlete_route(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/athlete/dashboard')
            ->assertStatus(403);
    }

    public function test_athlete_can_check_in_on_valid_session_of_own_club(): void
    {
        [$user, $athlete,, $location,, $session] = $this->makeValidSessionForAthlete();

        $this->actingAs($user)
            ->post('/athlete/attendance', [
                'training_session_id' => $session->id,
                'latitude' => -6.199500,
                'longitude' => 106.817000,
                'accuracy' => 8,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('attendances', [
            'athlete_id' => $athlete->id,
            'training_session_id' => $session->id,
            'location_status' => 'sesuai',
        ]);
    }

    public function test_athlete_can_choose_an_active_training_location_for_check_in(): void
    {
        [$user, $athlete,, $scheduledLocation,, $session] = $this->makeValidSessionForAthlete();
        $selectedLocation = TrainingLocation::factory()->create([
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'radius' => 100,
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->post('/athlete/attendance', [
                'training_session_id' => $session->id,
                'training_location_id' => $selectedLocation->id,
                'latitude' => -6.199500,
                'longitude' => 106.817000,
                'accuracy' => 8,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('attendances', [
            'athlete_id' => $athlete->id,
            'training_session_id' => $session->id,
            'training_location_id' => $selectedLocation->id,
        ]);

        $this->assertNotSame($scheduledLocation->id, $selectedLocation->id);
    }

    public function test_athlete_cannot_check_in_for_session_of_other_club(): void
    {
        $user = User::factory()->create(['role' => 'athlete']);
        $athlete = Athlete::factory()->create(['user_id' => $user->id, 'status' => 'active']);

        $otherClub = Club::factory()->create(['status' => 'active']);
        $location = TrainingLocation::factory()->create(['status' => 'active', 'radius' => 100]);
        $schedule = TrainingSchedule::factory()->create([
            'club_id' => $otherClub->id,
            'training_location_id' => $location->id,
            'status' => 'active',
        ]);
        $session = TrainingSession::factory()->create([
            'training_schedule_id' => $schedule->id,
            'date' => now()->toDateString(),
            'start_time' => now()->copy()->subMinute()->format('H:i:s'),
            'end_time' => now()->copy()->addMinutes(60)->format('H:i:s'),
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->from('/athlete/attendance')
            ->post('/athlete/attendance', [
                'training_session_id' => $session->id,
                'latitude' => -6.199500,
                'longitude' => 106.817000,
                'accuracy' => 8,
            ])
            ->assertSessionHasErrors(['training_session_id']);
    }

    public function test_athlete_cannot_check_in_twice_for_same_session(): void
    {
        [$user, $athlete,,,, $session] = $this->makeValidSessionForAthlete();

        $this->actingAs($user)->post('/athlete/attendance', [
            'training_session_id' => $session->id,
            'latitude' => -6.199500,
            'longitude' => 106.817000,
            'accuracy' => 8,
        ]);

        $this->actingAs($user)->from('/athlete/attendance')->post('/athlete/attendance', [
            'training_session_id' => $session->id,
            'latitude' => -6.200200,
            'longitude' => 106.816900,
            'accuracy' => 10,
        ])->assertSessionHasErrors(['training_session_id']);

        $this->assertSame(1, $athlete->attendances()->where('training_session_id', $session->id)->count());
    }

    public function test_session_not_active_is_rejected(): void
    {
        [$user,,,,, $session] = $this->makeValidSessionForAthlete();
        $session->update(['status' => 'cancelled']);

        $this->actingAs($user)
            ->post('/athlete/attendance', [
                'training_session_id' => $session->id,
                'latitude' => -6.199500,
                'longitude' => 106.817000,
                'accuracy' => 8,
            ])
            ->assertSessionHasErrors(['training_session_id']);
    }

    public function test_inactive_training_location_is_rejected(): void
    {
        [$user,,, $location,, $session] = $this->makeValidSessionForAthlete();
        $location->update(['status' => 'inactive']);

        $this->actingAs($user)
            ->post('/athlete/attendance', [
                'training_session_id' => $session->id,
                'latitude' => -6.199500,
                'longitude' => 106.817000,
                'accuracy' => 8,
            ])
            ->assertSessionHasErrors(['training_session_id']);
    }

    public function test_invalid_latitude_or_longitude_are_rejected(): void
    {
        [$user,,,,, $session] = $this->makeValidSessionForAthlete();

        $this->actingAs($user)
            ->post('/athlete/attendance', [
                'training_session_id' => $session->id,
                'latitude' => 91,
                'longitude' => 106.817000,
                'accuracy' => 8,
            ])
            ->assertSessionHasErrors(['latitude']);

        $this->actingAs($user)
            ->post('/athlete/attendance', [
                'training_session_id' => $session->id,
                'latitude' => -6.199500,
                'longitude' => 181,
                'accuracy' => 8,
            ])
            ->assertSessionHasErrors(['longitude']);
    }

    public function test_distance_is_saved_and_location_status_is_resolved(): void
    {
        [$user, $athlete,,,, $session] = $this->makeValidSessionForAthlete();

        $this->actingAs($user)
            ->post('/athlete/attendance', [
                'training_session_id' => $session->id,
                'latitude' => -6.200400,
                'longitude' => 106.817100,
                'accuracy' => 12,
            ]);

        $attendance = $athlete->attendances()->where('training_session_id', $session->id)->firstOrFail();

        $this->assertNotNull($attendance->distance_from_location);
        $this->assertGreaterThan(0, (float) $attendance->distance_from_location);
        $this->assertContains($attendance->location_status, ['sesuai', 'perlu_verifikasi']);
    }

    public function test_admin_can_see_attendance_monitoring_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/admin/attendances')
            ->assertOk();
    }

    public function test_admin_dashboard_requires_admin_role(): void
    {
        $user = User::factory()->create(['role' => 'athlete']);

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertStatus(403);
    }
}
