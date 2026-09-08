<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AthleteStoreRequest;
use App\Http\Requests\AthleteUpdateRequest;
use App\Models\Athlete;
use App\Models\Club;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AthleteController extends Controller
{
    public function index(): View
    {
        $athletes = Athlete::query()->with(['user', 'clubs'])->orderBy('created_at', 'desc')->get();

        return view('admin.athletes.index', compact('athletes'));
    }

    public function create(): View
    {
        $clubs = Club::query()->orderBy('name')->get();

        return view('admin.athletes.create', compact('clubs'));
    }

    public function store(AthleteStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password'] ?? 'password123'),
            'role' => 'athlete',
        ]);

        $athlete = $user->athlete()->create([
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'] ?? null,
            'class' => $validated['class'],
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
        ]);

        if (! empty($validated['club_ids'])) {
            $athlete->clubs()->sync($validated['club_ids']);
        }

        return redirect()->route('admin.athletes.index')->with('success', 'Data atlet berhasil ditambahkan.');
    }

    public function show(Athlete $athlete): View
    {
        return view('admin.athletes.show', compact('athlete'));
    }

    public function edit(Athlete $athlete): View
    {
        $clubs = Club::query()->orderBy('name')->get();

        return view('admin.athletes.edit', compact('athlete', 'clubs'));
    }

    public function update(AthleteUpdateRequest $request, Athlete $athlete): RedirectResponse
    {
        $validated = $request->validated();

        $athlete->user()->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'athlete',
        ]);

        if (! empty($validated['password'])) {
            $athlete->user()->update([
                'password' => bcrypt($validated['password']),
            ]);
        }

        $athlete->update([
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'] ?? null,
            'class' => $validated['class'],
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
        ]);

        if (isset($validated['club_ids'])) {
            $athlete->clubs()->sync($validated['club_ids']);
        }

        return redirect()->route('admin.athletes.index')->with('success', 'Data atlet berhasil diperbarui.');
    }

    public function destroy(Athlete $athlete): RedirectResponse
    {
        if ($athlete->user) {
            $athlete->user()->delete();
        }

        $athlete->delete();

        return redirect()->route('admin.athletes.index')->with('success', 'Data atlet berhasil dihapus.');
    }

    public function toggleStatus(Athlete $athlete): RedirectResponse
    {
        $athlete->update([
            'status' => $athlete->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Status atlet berhasil diperbarui.');
    }
}
