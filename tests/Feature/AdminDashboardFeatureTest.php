<?php

namespace Tests\Feature;

use App\Models\Athlete;
use App\Models\Attendance;
use App\Models\Club;
use App\Models\TrainingLocation;
use App\Models\TrainingSchedule;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk();
    }

    public function test_athlete_cannot_access_admin_dashboard(): void
    {
        $athlete = User::factory()->create(['role' => 'athlete']);

        $this->actingAs($athlete)
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_dashboard_statistics_are_calculated(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $club = Club::factory()->create(['status' => 'active']);
        $location = TrainingLocation::factory()->create(['status' => 'active']);
        $athlete = Athlete::factory()->create(['status' => 'active']);
        $athlete->clubs()->attach($club->id);

        $schedule = TrainingSchedule::factory()->create([
            'club_id' => $club->id,
            'training_location_id' => $location->id,
            'status' => 'active',
            'activity_type' => 'sekolah',
        ]);

        TrainingSession::factory()->count(2)->create([
            'training_schedule_id' => $schedule->id,
            'status' => 'active',
        ]);

        Attendance::factory()->count(2)->create([
            'attendance_status' => 'hadir',
            'location_status' => 'sesuai',
        ]);
        Attendance::factory()->create([
            'attendance_status' => 'izin',
            'location_status' => 'perlu_verifikasi',
        ]);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertViewHas('stats.totalActiveAthletes', Athlete::query()->where('status', 'active')->count())
            ->assertViewHas('stats.totalActiveClubs', Club::query()->where('status', 'active')->count())
            ->assertViewHas('stats.totalActiveLocations', TrainingLocation::query()->where('status', 'active')->count())
            ->assertViewHas('stats.totalActiveSchedules', TrainingSchedule::query()->where('status', 'active')->count())
            ->assertViewHas('stats.totalTrainingSessions', TrainingSession::query()->count())
            ->assertViewHas('stats.totalAttendances', Attendance::query()->count())
            ->assertViewHas('stats.totalNeedVerification', Attendance::query()->where('location_status', 'perlu_verifikasi')->count());
    }

    public function test_dashboard_shows_today_sessions_and_nearest_upcoming_sessions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $club = Club::factory()->create(['status' => 'active']);
        $location = TrainingLocation::factory()->create(['status' => 'active']);
        $schedule = TrainingSchedule::factory()->create([
            'club_id' => $club->id,
            'training_location_id' => $location->id,
            'activity_type' => 'luar_sekolah',
            'status' => 'active',
        ]);

        $today = TrainingSession::factory()->create([
            'training_schedule_id' => $schedule->id,
            'date' => now()->toDateString(),
            'status' => 'active',
        ]);

        TrainingSession::factory()->create([
            'training_schedule_id' => $schedule->id,
            'date' => now()->addDay()->toDateString(),
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertViewHas('todaySessions', fn ($sessions) => $sessions->contains(fn ($session) => $session->id === $today->id))
            ->assertViewHas('upcomingSessions', fn ($sessions) => $sessions->isNotEmpty());
    }

    public function test_dashboard_handles_empty_attendance_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertViewHas('attendanceSummary.hadir', 0)
            ->assertViewHas('attendanceSummary.terlambat', 0)
            ->assertViewHas('attendanceSummary.izin', 0)
            ->assertViewHas('attendanceSummary.sakit', 0)
            ->assertViewHas('attendanceSummary.alpa', 0)
            ->assertViewHas('attendanceSummary.perlu_verifikasi', 0);
    }
}
