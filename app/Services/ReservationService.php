<?php

namespace App\Services;

use App\Models\Track;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;

class ReservationService
{
    public function getAvailableTimesForDate(string $date): array
    {
        $date                  = Carbon::parse($date);
        $startPeriod           = $date->copy()->hour(9);
        $endPeriod             = $date->copy()->hour(17);
        $times                 = CarbonPeriod::create($startPeriod, '1 hour', $endPeriod);
        $availableReservations = [];

        $tracks = Track::with([
            'reservations' => function ($q) use ($startPeriod, $endPeriod) {
                $q->whereBetween('start_time', [$startPeriod, $endPeriod]);
            },
        ])
            ->get();

        foreach ($tracks as $track) {
            $reservations = $track->reservations->pluck('start_time')->toArray();

            $availableTimes = $times->copy()->filter(function ($time) use ($reservations) {
                return ! in_array($time, $reservations);
            })->toArray();

            foreach ($availableTimes as $time) {
                $key                         = $track->id . '-' . $time->format('H');
                $availableReservations[$key] = __('[Track :title]', ['title' => $track->title]) . $time->format('H:i');
            }
        }

        return $availableReservations;
    }
}
