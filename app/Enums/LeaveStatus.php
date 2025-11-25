<?php

namespace App\Enums;

enum LeaveStatus: string
{
    case PENDING = 'pending';
    case APPROVED_BY_LEADER = 'approved_by_leader';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
    
    /**
     * Get label
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Menunggu Persetujuan',
            self::APPROVED_BY_LEADER => 'Disetujui Ketua Divisi',
            self::APPROVED => 'Disetujui',
            self::REJECTED => 'Ditolak',
            self::CANCELLED => 'Dibatalkan',
        };
    }
    
    /**
     * Get badge color
     */
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::APPROVED_BY_LEADER => 'blue',
            self::APPROVED => 'green',
            self::REJECTED => 'red',
            self::CANCELLED => 'gray',
        };
    }
    
    /**
     * Check if can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return $this === self::PENDING;
    }
    
    /**
     * Check if is final status
     */
    public function isFinal(): bool
    {
        return in_array($this, [self::APPROVED, self::REJECTED, self::CANCELLED]);
    }
    
    /**
     * Check if is approved (any level)
     */
    public function isApproved(): bool
    {
        return in_array($this, [self::APPROVED_BY_LEADER, self::APPROVED]);
    }
    
    /**
     * Get all values
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    /**
     * Get options for filter
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(function ($case) {
            return [$case->value => $case->label()];
        })->toArray();
    }
}