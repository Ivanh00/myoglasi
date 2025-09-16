<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function diffForHumansSr($date)
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $now = Carbon::now();
        $diff = $now->diff($date);
        $future = $date->isFuture();

        // Calculate total days for accurate representation
        $totalDays = $diff->days;
        $hours = $diff->h;
        $minutes = $diff->i;

        // Build the string
        $result = '';

        if ($totalDays == 0 && $hours == 0 && $minutes < 60) {
            // Less than an hour
            if ($minutes == 0) {
                $result = 'sada';
            } elseif ($minutes == 1) {
                $result = '1 minut';
            } else {
                $result = $minutes . ' minuta';
            }
        } elseif ($totalDays == 0 && $hours < 24) {
            // Less than a day
            if ($hours == 1) {
                $result = '1 sat';
            } elseif ($hours < 5) {
                $result = $hours . ' sata';
            } else {
                $result = $hours . ' sati';
            }
        } else {
            // Days
            if ($totalDays == 1) {
                $result = '1 dan';
            } elseif ($totalDays < 5) {
                $result = $totalDays . ' dana';
            } elseif ($totalDays == 21) {
                $result = '21 dan';
            } elseif ($totalDays == 31) {
                $result = '31 dan';
            } else {
                $result = $totalDays . ' dana';
            }
        }

        // Add prefix for future/past
        if ($future) {
            return 'za ' . $result;
        } else {
            return 'pre ' . $result;
        }
    }
}