<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_id',
        'training_session_id',
        'training_location_id',
        'attendance_status',
        'check_in_at',
        'latitude',
        'longitude',
        'gps_accuracy',
        'distance_from_location',
        'location_status',
        'note',
        'verification_status',
        'verification_note',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'check_in_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }

    public function trainingSession(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class);
    }

    public function trainingLocation(): BelongsTo
    {
        return $this->belongsTo(TrainingLocation::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public static function resolveLocationStatus(float $distance, float $radius): string
    {
        return $distance <= $radius ? 'sesuai' : 'perlu_verifikasi';
    }
}
