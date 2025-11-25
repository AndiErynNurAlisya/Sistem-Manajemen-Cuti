<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class WorkingDayCalculator
{
    /**
     * Hitung total hari kerja (Senin-Jumat) antara 2 tanggal
     * 
     * @param string|Carbon $startDate
     * @param string|Carbon $endDate
     * @return int
     */
    public function calculate($startDate, $endDate): int
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $workingDays = 0;
        
        // Loop dari start sampai end
        $period = CarbonPeriod::create($start, $end);
        
        foreach ($period as $date) {
            // Skip weekend (Sabtu & Minggu)
            if (!$date->isWeekend()) {
                $workingDays++;
            }
        }
        
        return $workingDays;
    }
    
    /**
     * Validasi apakah tanggal adalah hari kerja
     * 
     * @param string|Carbon $date
     * @return bool
     */
    public function isWorkingDay($date): bool
    {
        $date = Carbon::parse($date);
        return !$date->isWeekend();
    }
    
    /**
     * Validasi H+3 untuk cuti tahunan
     * 
     * @param string|Carbon $startDate
     * @return bool
     */
    public function isValidAnnualLeaveDate($startDate): bool
    {
        $start = Carbon::parse($startDate);
        $minDate = now()->addDays(3)->startOfDay();
        
        return $start->greaterThanOrEqualTo($minDate);
    }
    
    /**
     * Validasi H-0 untuk cuti sakit (bisa hari ini)
     * 
     * @param string|Carbon $startDate
     * @return bool
     */
    public function isValidSickLeaveDate($startDate): bool
    {
        $start = Carbon::parse($startDate);
        $today = now()->startOfDay();
        
        return $start->greaterThanOrEqualTo($today);
    }
    
    /**
     * Get hari kerja berikutnya
     * 
     * @param string|Carbon|null $date
     * @return Carbon
     */
    public function nextWorkingDay($date = null): Carbon
    {
        $date = $date ? Carbon::parse($date) : now();
        $date = $date->copy()->addDay();
        
        while ($date->isWeekend()) {
            $date->addDay();
        }
        
        return $date;
    }
    
    /**
     * Get hari kerja sebelumnya
     * 
     * @param string|Carbon|null $date
     * @return Carbon
     */
    public function previousWorkingDay($date = null): Carbon
    {
        $date = $date ? Carbon::parse($date) : now();
        $date = $date->copy()->subDay();
        
        while ($date->isWeekend()) {
            $date->subDay();
        }
        
        return $date;
    }
    
    /**
     * Check if date range contains working days
     * 
     * @param string|Carbon $startDate
     * @param string|Carbon $endDate
     * @return bool
     */
    public function hasWorkingDays($startDate, $endDate): bool
    {
        return $this->calculate($startDate, $endDate) > 0;
    }
}