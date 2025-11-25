<?php

namespace App\Enums;

enum LeaveType: string
{
    case ANNUAL = 'annual';
    case SICK = 'sick';
    
    /**
     * Get label
     */
    public function label(): string
    {
        return match($this) {
            self::ANNUAL => 'Cuti Tahunan',
            self::SICK => 'Cuti Sakit',
        };
    }
    
    /**
     * Check if requires medical certificate
     */
    public function requiresMedicalCertificate(): bool
    {
        return $this === self::SICK;
    }
    
    /**
     * Check if deducts quota
     */
    public function deductsQuota(): bool
    {
        return $this === self::ANNUAL;
    }
    
    /**
     * Get minimum days before leave (H+X)
     */
    public function minimumDaysBefore(): int
    {
        return match($this) {
            self::ANNUAL => 3,  // H+3
            self::SICK => 0,    // H-0 (bisa hari ini)
        };
    }
    
    /**
     * Get color for badge
     */
    public function color(): string
    {
        return match($this) {
            self::ANNUAL => 'blue',
            self::SICK => 'red',
        };
    }
    
    /**
     * Get all values
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    /**
     * Get options for select
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(function ($case) {
            return [$case->value => $case->label()];
        })->toArray();
    }
}