<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'leader_id',
        'established_date',
    ];

    protected $casts = [
        'established_date' => 'date',
    ];

    /**
     * RELASI: Division belongs to User (leader)
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * RELASI: Division has many Users (members)
     */
    public function members(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * RELASI: Division has many active members
     */
    public function activeMembers(): HasMany
    {
        return $this->hasMany(User::class)->where('is_active', true);
    }

    /**
     * ACCESSOR: Get member count
     */
    public function getMemberCountAttribute(): int
    {
        return $this->members()->count();
    }

    /**
     * ACCESSOR: Get active member count
     */
    public function getActiveMemberCountAttribute(): int
    {
        return $this->activeMembers()->count();
    }

    /**
     * SCOPE: With member count
     */
    public function scopeWithMemberCount($query)
    {
        return $query->withCount('members');
    }

    /**
     * Check if division has leader
     */
    public function hasLeader(): bool
    {
        return !is_null($this->leader_id);
    }
}