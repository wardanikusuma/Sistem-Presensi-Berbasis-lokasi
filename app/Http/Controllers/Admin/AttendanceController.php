<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Club;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Attendance::query()
            ->with([
                'athlete.user',
                'trainingSession.trainingSchedule.club',
                'trainingLocation',
                'verifier',
            ]);

        $this->applyFilters($query, $request);

        $attendances = $query
            ->orderByDesc('check_in_at')
            ->paginate(20)
            ->appends($request->query());

        $clubs = Club::query()->orderBy('name')->get();

        return view('admin.attendances', compact('attendances', 'clubs'));
    }

    public function show(Attendance $attendance): View
    {
        $attendance->load([
            'athlete.user',
            'trainingSession.trainingSchedule.club',
            'trainingLocation',
            'verifier',
        ]);

        return view('admin.attendances.show', compact('attendance'));
    }

    public function verify(Request $request, Attendance $attendance): RedirectResponse
    {
        $validated = $request->validate([
            'verification_status' => ['required', 'in:verified,rejected,needs_review'],
            'verification_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $attendance->update([
            'verification_status' => $validated['verification_status'],
            'verification_note' => $validated['verification_note'] ?? null,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'location_status' => $validated['verification_status'] === 'verified' ? 'sesuai' : 'perlu_verifikasi',
        ]);

        return redirect()->route('admin.attendances.show', $attendance)
            ->with('success', 'Status verifikasi presensi berhasil diperbarui.');
    }

    public function reports(Request $request): View
    {
        $query = Attendance::query()->with([
            'athlete.user',
            'trainingSession.trainingSchedule.club',
            'trainingLocation',
        ]);

        $this->applyFilters($query, $request);

        $summary = [
            'hadir' => (clone $query)->where('attendance_status', 'hadir')->count(),
            'terlambat' => (clone $query)->where('attendance_status', 'terlambat')->count(),
            'izin' => (clone $query)->where('attendance_status', 'izin')->count(),
            'sakit' => (clone $query)->where('attendance_status', 'sakit')->count(),
            'alpa' => (clone $query)->where('attendance_status', 'alpa')->count(),
            'perlu_verifikasi' => (clone $query)->where('location_status', 'perlu_verifikasi')->count(),
            'sesuai' => (clone $query)->where('location_status', 'sesuai')->count(),
        ];

        $clubs = Club::query()->orderBy('name')->get();

        return view('admin.reports', compact('summary', 'clubs'));
    }

    public function exportCsv(Request $request)
    {
        $query = Attendance::query()->with([
            'athlete.user',
            'trainingSession.trainingSchedule.club',
            'trainingLocation',
        ]);

        $this->applyFilters($query, $request);

        $filename = 'presensi-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $rows = $query->orderByDesc('check_in_at')->get();

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Atlet', 'Klub', 'Sesi', 'Tanggal', 'Status Kehadiran', 'Lokasi', 'Status Lokasi', 'Jarak (m)', 'Accuracy (m)', 'Verifikasi', 'Catatan']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->athlete?->user?->name ?? '-',
                    $row->trainingSession?->trainingSchedule?->club?->name ?? '-',
                    $row->trainingSession?->trainingSchedule?->name ?? '-',
                    $row->check_in_at ? $row->check_in_at->format('Y-m-d H:i:s') : '-',
                    $row->attendance_status,
                    $row->trainingLocation?->name ?? '-',
                    $row->location_status,
                    $row->distance_from_location ?? '-',
                    $row->gps_accuracy ?? '-',
                    $row->verification_status ?? 'pending',
                    $row->note ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    protected function applyFilters($query, Request $request): void
    {
        if ($request->filled('search')) {
            $search = trim($request->string('search'));

            $query->whereHas('athlete.user', function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('club_id')) {
            $query->whereHas('trainingSession.trainingSchedule', function ($subQuery) use ($request) {
                $subQuery->where('club_id', $request->input('club_id'));
            });
        }

        if ($request->filled('location_status')) {
            $query->where('location_status', $request->input('location_status'));
        }

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->input('verification_status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('check_in_at', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('check_in_at', '<=', $request->date('date_to'));
        }
    }
}
