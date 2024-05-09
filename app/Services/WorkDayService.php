<?php

namespace App\Services;

use App\Models\Holiday;
use Carbon\Carbon;

class WorkDayService {
    public function isWorkDay($date): bool
    {

        $date = Carbon::parse($date);

        $czechHolidays = Holiday::where('state', 'CZ')->pluck('holiday_date')->toArray();

        if (in_array($date->toDateString(), $czechHolidays)) {
            return false;
        }

        if ($date->isWeekend()) {
            return false;
        }

        return true;
    }

    public function calculateCompletionDate($startDate, $durationMinutes, $considerHolidays, $workTimeStart, $workTimeEnd): string {
        $startDate = Carbon::parse($startDate);
        $workTimeStart = Carbon::parse($workTimeStart);
        $workTimeEnd = Carbon::parse($workTimeEnd);

        $workDayDuration = $workTimeStart->diffInMinutes($workTimeEnd);

        if ($workDayDuration <= 0) {
            return "Error: negative work day span\n";
        }

        $remainingMinutes = $durationMinutes;
        $currentDay = $startDate;

        while ($remainingMinutes > 0) {

            $currentDay = $currentDay->addDays();
            if ($this->isWorkDay($currentDay) || !$considerHolidays) {
                $remainingMinutes = $remainingMinutes - $workDayDuration;
            }
        }

        return $currentDay;
    }
}
