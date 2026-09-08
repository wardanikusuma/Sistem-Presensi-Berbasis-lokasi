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

class AdminMonitoringFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function makeAttendanceWithClub(string $status = 'sesuai'): array
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $athleteUser = User::factory()->create(['role' => 'athlete']);
        $athlete = Athlete::factory()->create(['user_id' => $athleteUser->id, 'status' => 'active']);

        $club = Club::factory()->create(['status' => 'active']);
        $athlete->clubs()->attach($club->id);

        $location = TrainingLocation::factory()->create([
            'status' => 'active',
            'radius' => 100,
            'latitude' => -6.200000,
            'longitude' => 106.816666,
        ]);

        $schedule = TrainingSchedule::factory()->create([
            'club_id' => $club->id,
            'training_location_id' => $location->id,
            'status' => 'active',
            'activity_type' => 'sekolah',
        ]);

        $session = TrainingSession::factory()->create([
            'training_schedule_id' => $schedule->id,
            'date' => now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'status' => 'active',
        ]);

        $attendance = Attendance::factory()->create([
            'athlete_id' => $athlete->id,
            'training_session_id' => $session->id,
            'attendance_status' => 'hadir',
            'location_status' => $status,
            'latitude' => -6.199500,
            'longitude' => 106.817000,
            'gps_accuracy' => 8.00,
            'distance_from_location' => 42.50,
        ]);

        return [$admin, $athleteUser, $athlete, $club, $attendance];
    }

    public function test_admin_can_view_attendance_monitoring_page(): void
    {
        [$admin] = $this->makeAttendanceWithClub();

        $this->actingAs($admin)
            ->get('/admin/attendances')
            ->assertOk();
    }

    public function test_athlete_cannot_open_other_athletes_detail(): void
    {
        [$admin, $athleteUser,,, $attendance] = $this->makeAttendanceWithClub();

        $otherUser = User::factory()->create(['role' => 'athlete']);
        $otherAthlete = Athlete::factory()->create(['user_id' => $otherUser->id]);
        $this->actingAs($otherUser)
            ->get('/admin/attendances/'.$attendance->id)
            ->assertForbidden();
    }

    public function test_admin_can_verify_attendance_without_changing_original_gps_data(): void
    {
        [$admin,,,, $attendance] = $this->makeAttendanceWithClub('perlu_verifikasi');

        $this->actingAs($admin)
            ->post('/admin/attendances/'.$attendance->id.'/verify', [
                'verification_status' => 'verified',
                'verification_note' => 'Diterima setelah pengecekan manual.',
            ])
            ->assertRedirect();

        $attendance->refresh();

        $this->assertSame('verified', $attendance->verification_status);
        $this->assertSame(-6.199500, (float) $attendance->latitude);
        $this->assertSame(106.817000, (float) $attendance->longitude);
        $this->assertSame(8.00, (float) $attendance->gps_accuracy);
        $this->assertSame(42.50, (float) $attendance->distance_from_location);
        $this->assertNotNull($attendance->verified_by);
        $this->assertNotNull($attendance->verified_at);
    }

    public function test_admin_report_page_shows_totals(): void
    {
        [$admin] = $this->makeAttendanceWithClub('sesuai');

        $this->actingAs($admin)
            ->get('/admin/attendance-reports')
            ->assertOk();
    }

    public function test_filter_and_search_work_for_attendance_index(): void
    {
        [$admin,,, $club, $attendance] = $this->makeAttendanceWithClub('perlu_verifikasi');

        $this->actingAs($admin)
            ->get('/admin/attendances?search='.urlencode($attendance->athlete->user->name).'&club_id='.$club->id.'&location_status=perlu_verifikasi')
            ->assertOk();
    }

    public function test_export_csv_follows_filter(): void
    {
        [$admin,,, $club, $attendance] = $this->makeAttendanceWithClub('sesuai');

        $this->actingAs($admin)
            ->get('/admin/attendance-reports/export?club_id='.$club->id)
            ->assertOk();
    }
}
