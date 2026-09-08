<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrainingSessionStoreRequest;
use App\Http\Requests\TrainingSessionUpdateRequest;
use App\Models\TrainingSchedule;
use App\Models\TrainingSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrainingSessionController extends Controller
{
    public function index(): View
    {
        $trainingSessions = TrainingSession::query()->with('trainingSchedule.club')->orderBy('date', 'desc')->get();

        return view('admin.training-sessions.index', compact('trainingSessions'));
    }

    public function create(): View
    {
        $trainingSchedules = TrainingSchedule::query()->with(['club', 'trainingLocation'])->orderBy('day_of_week')->get();

        return view('admin.training-sessions.create', compact('trainingSchedules'));
    }

    public function store(TrainingSessionStoreRequest $request): RedirectResponse
    {
        TrainingSession::query()->create($request->validated());

        return redirect()->route('admin.training-sessions.index')->with('success', 'Sesi latihan berhasil ditambahkan.');
    }

    public function show(TrainingSession $trainingSession): View
    {
        $trainingSession->load('trainingSchedule.club');

        return view('admin.training-sessions.show', compact('trainingSession'));
    }

    public function edit(TrainingSession $trainingSession): View
    {
        $trainingSchedules = TrainingSchedule::query()->with(['club', 'trainingLocation'])->orderBy('day_of_week')->get();

        return view('admin.training-sessions.edit', compact('trainingSession', 'trainingSchedules'));
    }

    public function update(TrainingSessionUpdateRequest $request, TrainingSession $trainingSession): RedirectResponse
    {
        $trainingSession->update($request->validated());

        return redirect()->route('admin.training-sessions.index')->with('success', 'Sesi latihan berhasil diperbarui.');
    }

    public function destroy(TrainingSession $trainingSession): RedirectResponse
    {
        if ($trainingSession->attendances()->exists()) {
            return back()->withErrors(['delete' => 'Sesi latihan tidak dapat dihapus karena sudah memiliki data presensi.']);
        }

        $trainingSession->delete();

        return redirect()->route('admin.training-sessions.index')->with('success', 'Sesi latihan berhasil dihapus.');
    }

    public function toggleStatus(TrainingSession $trainingSession): RedirectResponse
    {
        $trainingSession->update([
            'status' => $trainingSession->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Status sesi berhasil diperbarui.');
    }
}
