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

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function activeMembers(): HasMany
    {
        return $this->hasMany(User::class)->where('is_active', true);
    }

    public function getMemberCountAttribute(): int
    {
        return $this->members()->count();
    }

    public function getActiveMemberCountAttribute(): int
    {
        return $this->activeMembers()->count();
    }

    public function scopeWithMemberCount($query)
    {
        return $query->withCount('members');
    }

    public function hasLeader(): bool
    {
        return !is_null($this->leader_id);
    }
}
