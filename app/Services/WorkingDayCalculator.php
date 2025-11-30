<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class WorkingDayCalculator
{

    public function calculate($startDate, $endDate): int
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $workingDays = 0;
        

        $period = CarbonPeriod::create($start, $end);
        
        foreach ($period as $date) {
            if (!$date->isWeekend()) {
                $workingDays++;
            }
        }
        
        return $workingDays;
    }
    

    public function isWorkingDay($date): bool
    {
        $date = Carbon::parse($date);
        return !$date->isWeekend();
    }
    

    public function isValidAnnualLeaveDate($startDate): bool
    {
        $start = Carbon::parse($startDate);
        $minDate = now()->addDays(3)->startOfDay();
        
        return $start->greaterThanOrEqualTo($minDate);
    }
    

    public function isValidSickLeaveDate($startDate): bool
    {
        $start = Carbon::parse($startDate);
        $today = now()->startOfDay();
        
        return $start->greaterThanOrEqualTo($today);
    }
    

    public function nextWorkingDay($date = null): Carbon
    {
        $date = $date ? Carbon::parse($date) : now();
        $date = $date->copy()->addDay();
        
        while ($date->isWeekend()) {
            $date->addDay();
        }
        
        return $date;
    }
    

    public function previousWorkingDay($date = null): Carbon
    {
        $date = $date ? Carbon::parse($date) : now();
        $date = $date->copy()->subDay();
        
        while ($date->isWeekend()) {
            $date->subDay();
        }
        
        return $date;
    }
    
    public function hasWorkingDays($startDate, $endDate): bool
    {
        return $this->calculate($startDate, $endDate) > 0;
    }
}