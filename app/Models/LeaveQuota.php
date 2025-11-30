<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
        'total_quota',
        'used_quota',
        'remaining_quota',
    ];

    protected $casts = [
        'year' => 'integer',
        'total_quota' => 'integer',
        'used_quota' => 'integer',
        'remaining_quota' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPercentageUsedAttribute(): float
    {
        if ($this->total_quota == 0) {
            return 0;
        }

        return round(($this->used_quota / $this->total_quota) * 100, 2);
    }

    public function isSufficient(int $days): bool
    {
        return $this->remaining_quota >= $days;
    }

    public function isExhausted(): bool
    {
        return $this->remaining_quota <= 0;
    }

    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    public function scopeCurrentYear($query)
    {
        return $query->where('year', now()->year);
    }
}
