<?php

namespace App\Http\Controllers\Athlete;

use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $athlete = $request->user()->athlete()->with('clubs')->firstOrFail();
        $clubIds = $athlete->clubs()->pluck('clubs.id')->all();

        $sessions = TrainingSession::query()
            ->with(['trainingSchedule.club', 'trainingSchedule.trainingLocation'])
            ->where('status', 'active')
            ->whereHas('trainingSchedule', fn ($query) => $query->where('status', 'active'))
            ->whereHas('trainingSchedule.trainingLocation', fn ($query) => $query->where('status', 'active'))
            ->whereDate('date', '>=', today())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->filter(fn (TrainingSession $session) => in_array($session->trainingSchedule->club_id, $clubIds, true));

        return view('athlete.schedules', compact('sessions'));
    }
}
