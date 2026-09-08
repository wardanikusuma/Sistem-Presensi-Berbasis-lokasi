<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Athlete extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'class',
        'phone',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clubs(): BelongsToMany
    {
        return $this->belongsToMany(Club::class, 'athlete_club');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function markAttendance(TrainingSession $session, array $payload): bool|Attendance
    {
        if ($this->attendances()->where('training_session_id', $session->id)->exists()) {
            return false;
        }

        $trainingLocation = $session->trainingSchedule->trainingLocation;

        $distance = Attendance::calculateDistance(
            (float) $payload['latitude'],
            (float) $payload['longitude'],
            (float) $trainingLocation->latitude,
            (float) $trainingLocation->longitude,
        );

        $locationStatus = Attendance::resolveLocationStatus($distance, (float) $trainingLocation->radius);

        return Attendance::create([
            'athlete_id' => $this->id,
            'training_session_id' => $session->id,
            'attendance_status' => 'hadir',
            'check_in_at' => now(),
            'latitude' => $payload['latitude'],
            'longitude' => $payload['longitude'],
            'gps_accuracy' => $payload['accuracy'] ?? null,
            'distance_from_location' => round($distance, 2),
            'location_status' => $locationStatus,
            'note' => $payload['note'] ?? null,
        ]);
    }
}
