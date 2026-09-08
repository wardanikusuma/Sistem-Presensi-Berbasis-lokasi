<?php

namespace App\Services;

class LocationDistanceService
{
    public function calculate(float $latitudeFrom, float $longitudeFrom, float $latitudeTo, float $longitudeTo): float
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($latitudeTo - $latitudeFrom);
        $dLon = deg2rad($longitudeTo - $longitudeFrom);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function resolveLocationStatus(float $distance, float $radius): string
    {
        return $distance <= $radius ? 'sesuai' : 'perlu_verifikasi';
    }
}
