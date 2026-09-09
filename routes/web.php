<?php

use App\Http\Controllers\Admin\AthleteController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\ClubController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TrainingLocationController;
use App\Http\Controllers\Admin\TrainingScheduleController;
use App\Http\Controllers\Admin\TrainingSessionController;
use App\Http\Controllers\Athlete\AthleteDashboardController;
use App\Http\Controllers\Athlete\AthleteProfileController;
use App\Http\Controllers\Athlete\AttendanceController;
use App\Http\Controllers\Athlete\ClubController as AthleteClubController;
use App\Http\Controllers\Athlete\ScheduleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('athlete.dashboard');
    })->name('dashboard');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        })->name('index');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/monitoring', [AdminAttendanceController::class, 'index'])->name('monitoring');
        Route::get('/attendances', [AdminAttendanceController::class, 'index'])->name('attendances');
        Route::get('/attendances/{attendance}', [AdminAttendanceController::class, 'show'])->name('attendances.show');
        Route::post('/attendances/{attendance}/verify', [AdminAttendanceController::class, 'verify'])->name('attendances.verify');
        Route::get('/attendance-reports', [AdminAttendanceController::class, 'reports'])->name('attendance-reports');
        Route::get('/attendance-reports/export', [AdminAttendanceController::class, 'exportCsv'])->name('attendance-reports.export');

        Route::resource('clubs', ClubController::class);
        Route::patch('clubs/{club}/toggle-status', [ClubController::class, 'toggleStatus'])->name('clubs.toggle-status');

        Route::resource('athletes', AthleteController::class);
        Route::patch('athletes/{athlete}/toggle-status', [AthleteController::class, 'toggleStatus'])->name('athletes.toggle-status');

        Route::resource('training-locations', TrainingLocationController::class)->parameters([
            'training-locations' => 'trainingLocation',
        ]);
        Route::patch('training-locations/{trainingLocation}/toggle-status', [TrainingLocationController::class, 'toggleStatus'])->name('training-locations.toggle-status');

        Route::resource('training-schedules', TrainingScheduleController::class)->parameters([
            'training-schedules' => 'trainingSchedule',
        ]);
        Route::patch('training-schedules/{trainingSchedule}/toggle-status', [TrainingScheduleController::class, 'toggleStatus'])->name('training-schedules.toggle-status');

        Route::resource('training-sessions', TrainingSessionController::class)->parameters([
            'training-sessions' => 'trainingSession',
        ]);
        Route::patch('training-sessions/{trainingSession}/toggle-status', [TrainingSessionController::class, 'toggleStatus'])->name('training-sessions.toggle-status');
    });

    Route::middleware('role:athlete')->prefix('athlete')->name('athlete.')->group(function () {
        Route::get('/dashboard', [AthleteDashboardController::class, 'index'])->name('dashboard');
        Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules');
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendances', [AttendanceController::class, 'history'])->name('attendances');
        Route::get('/clubs', [AthleteClubController::class, 'index'])->name('clubs');
        Route::get('/profile', [AthleteProfileController::class, 'index'])->name('profile');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
