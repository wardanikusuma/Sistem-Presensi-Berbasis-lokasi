<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrainingScheduleStoreRequest;
use App\Http\Requests\TrainingScheduleUpdateRequest;
use App\Models\Club;
use App\Models\TrainingLocation;
use App\Models\TrainingSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrainingScheduleController extends Controller
{
    public function index(): View
    {
        $trainingSchedules = TrainingSchedule::query()->with(['club', 'trainingLocation'])->orderBy('day_of_week')->get();

        return view('admin.training-schedules.index', compact('trainingSchedules'));
    }

    public function create(): View
    {
        $clubs = Club::query()->orderBy('name')->get();
        $trainingLocations = TrainingLocation::query()->orderBy('name')->get();

        return view('admin.training-schedules.create', compact('clubs', 'trainingLocations'));
    }

    public function store(TrainingScheduleStoreRequest $request): RedirectResponse
    {
        TrainingSchedule::query()->create($request->validated());

        return redirect()->route('admin.training-schedules.index')->with('success', 'Jadwal latihan berhasil ditambahkan.');
    }

    public function show(TrainingSchedule $trainingSchedule): View
    {
        return view('admin.training-schedules.show', compact('trainingSchedule'));
    }

    public function edit(TrainingSchedule $trainingSchedule): View
    {
        $clubs = Club::query()->orderBy('name')->get();
        $trainingLocations = TrainingLocation::query()->orderBy('name')->get();

        return view('admin.training-schedules.edit', compact('trainingSchedule', 'clubs', 'trainingLocations'));
    }

    public function update(TrainingScheduleUpdateRequest $request, TrainingSchedule $trainingSchedule): RedirectResponse
    {
        $trainingSchedule->update($request->validated());

        return redirect()->route('admin.training-schedules.index')->with('success', 'Jadwal latihan berhasil diperbarui.');
    }

    public function destroy(TrainingSchedule $trainingSchedule): RedirectResponse
    {
        if ($trainingSchedule->trainingSessions()->exists()) {
            return back()->withErrors(['delete' => 'Jadwal latihan tidak dapat dihapus karena masih memiliki sesi latihan.']);
        }

        $trainingSchedule->delete();

        return redirect()->route('admin.training-schedules.index')->with('success', 'Jadwal latihan berhasil dihapus.');
    }

    public function toggleStatus(TrainingSchedule $trainingSchedule): RedirectResponse
    {
        $trainingSchedule->update([
            'status' => $trainingSchedule->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Status jadwal berhasil diperbarui.');
    }
}
