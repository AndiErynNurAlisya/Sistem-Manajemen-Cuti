<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case EMPLOYEE = 'employee';
    case LEADER = 'leader';
    case HRD = 'hrd';
    
    /**
     * Get label untuk tampilan
     */
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::EMPLOYEE => 'Karyawan',
            self::LEADER => 'Ketua Divisi',
            self::HRD => 'Human Resource',
        };
    }
    
    /**
     * Get redirect route setelah login
     */
    public function dashboardRoute(): string
    {
        return match($this) {
            self::ADMIN => 'admin.dashboard',
            self::EMPLOYEE => 'employee.dashboard',
            self::LEADER => 'leader.dashboard',
            self::HRD => 'hrd.dashboard',
        };
    }
    
    /**
     * Check if can request leave
     */
    public function canRequestLeave(): bool
    {
        return in_array($this, [self::EMPLOYEE, self::LEADER]);
    }
    
    /**
     * Get all values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    /**
     * Get options for select dropdown
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(function ($case) {
            return [$case->value => $case->label()];
        })->toArray();
    }
}