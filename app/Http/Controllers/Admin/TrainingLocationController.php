<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrainingLocationStoreRequest;
use App\Http\Requests\TrainingLocationUpdateRequest;
use App\Models\TrainingLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrainingLocationController extends Controller
{
    public function index(): View
    {
        $trainingLocations = TrainingLocation::query()->orderBy('name')->get();

        return view('admin.training-locations.index', compact('trainingLocations'));
    }

    public function create(): View
    {
        return view('admin.training-locations.create');
    }

    public function store(TrainingLocationStoreRequest $request): RedirectResponse
    {
        TrainingLocation::query()->create($request->validated());

        return redirect()->route('admin.training-locations.index')->with('success', 'Lokasi latihan berhasil ditambahkan.');
    }

    public function show(TrainingLocation $trainingLocation): View
    {
        return view('admin.training-locations.show', compact('trainingLocation'));
    }

    public function edit(TrainingLocation $trainingLocation): View
    {
        return view('admin.training-locations.edit', compact('trainingLocation'));
    }

    public function update(TrainingLocationUpdateRequest $request, TrainingLocation $trainingLocation): RedirectResponse
    {
        $trainingLocation->update($request->validated());

        return redirect()->route('admin.training-locations.index')->with('success', 'Lokasi latihan berhasil diperbarui.');
    }

    public function destroy(TrainingLocation $trainingLocation): RedirectResponse
    {
        if ($trainingLocation->trainingSchedules()->exists()) {
            return back()->withErrors(['delete' => 'Lokasi latihan tidak dapat dihapus karena masih digunakan pada jadwal latihan.']);
        }

        $trainingLocation->delete();

        return redirect()->route('admin.training-locations.index')->with('success', 'Lokasi latihan berhasil dihapus.');
    }

    public function toggleStatus(TrainingLocation $trainingLocation): RedirectResponse
    {
        $trainingLocation->update([
            'status' => $trainingLocation->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Status lokasi berhasil diperbarui.');
    }
}
