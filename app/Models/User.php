<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\UserRole;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'full_name',
        'phone',
        'address',
        'profile_photo',
        'role',
        'division_id',
        'is_active',
        'join_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'join_date' => 'date',
        'role' => UserRole::class,
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveQuota(): HasOne
    {
        return $this->hasOne(LeaveQuota::class)->where('year', now()->year);
    }

    public function leaveQuotas(): HasMany
    {
        return $this->hasMany(LeaveQuota::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(LeaveApproval::class, 'approver_id');
    }

    public function leadingDivision(): HasOne
    {
        return $this->hasOne(Division::class, 'leader_id');
    }

    public function getRoleLabelAttribute(): string
    {
        return $this->role->label();
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithoutDivision($query)
    {
        return $query->whereNull('division_id');
    }

    public function canRequestLeave(): bool
    {
        return $this->role->canRequestLeave();
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isHRD(): bool
    {
        return $this->role === UserRole::HRD;
    }

    public function isLeader(): bool
    {
        return $this->role === UserRole::LEADER;
    }

    public function isEmployee(): bool
    {
        return $this->role === UserRole::EMPLOYEE;
    }

    public function canBeDeleted(): bool
    {
        return in_array($this->role->value, ['employee', 'leader']);
    }
}
