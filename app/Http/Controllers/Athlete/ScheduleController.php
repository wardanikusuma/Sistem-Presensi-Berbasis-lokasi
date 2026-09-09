<?php

namespace App\Http\Controllers\Athlete;

use App\Http\Controllers\Controller;
use App\Models\TrainingSchedule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $athlete = $request->user()->athlete()->with('clubs')->firstOrFail();
        $clubIds = $athlete->clubs()->pluck('clubs.id')->all();

        $schedules = TrainingSchedule::query()
            ->with(['club', 'trainingLocation'])
            ->where('status', 'active')
            ->whereIn('club_id', $clubIds)
            ->orderByRaw("CASE day_of_week
                WHEN 'Senin' THEN 1
                WHEN 'Selasa' THEN 2
                WHEN 'Rabu' THEN 3
                WHEN 'Kamis' THEN 4
                WHEN 'Jumat' THEN 5
                WHEN 'Sabtu' THEN 6
                WHEN 'Minggu' THEN 7
                ELSE 8 END")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('athlete.schedules', compact('schedules', 'daysOfWeek'));
    }
}
