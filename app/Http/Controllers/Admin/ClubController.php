<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClubStoreRequest;
use App\Http\Requests\ClubUpdateRequest;
use App\Models\Club;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClubController extends Controller
{
    public function index(): View
    {
        $clubs = Club::query()->orderBy('name')->get();

        return view('admin.clubs.index', compact('clubs'));
    }

    public function create(): View
    {
        return view('admin.clubs.create');
    }

    public function store(ClubStoreRequest $request): RedirectResponse
    {
        Club::query()->create($request->validated());

        return redirect()->route('admin.clubs.index')->with('success', 'Klub berhasil ditambahkan.');
    }

    public function show(Club $club): View
    {
        return view('admin.clubs.show', compact('club'));
    }

    public function edit(Club $club): View
    {
        return view('admin.clubs.edit', compact('club'));
    }

    public function update(ClubUpdateRequest $request, Club $club): RedirectResponse
    {
        $club->update($request->validated());

        return redirect()->route('admin.clubs.index')->with('success', 'Klub berhasil diperbarui.');
    }

    public function destroy(Club $club): RedirectResponse
    {
        if ($club->athletes()->exists() || $club->trainingSchedules()->exists()) {
            return back()->withErrors(['delete' => 'Klub tidak dapat dihapus karena masih terhubung dengan data atlet atau jadwal latihan.']);
        }

        $club->delete();

        return redirect()->route('admin.clubs.index')->with('success', 'Klub berhasil dihapus.');
    }

    public function toggleStatus(Club $club): RedirectResponse
    {
        $club->update([
            'status' => $club->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Status klub berhasil diperbarui.');
    }
}
