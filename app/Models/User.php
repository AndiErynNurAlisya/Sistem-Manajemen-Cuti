<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\UserRole;

class User extends Authenticatable
{
    //HasApiTokens, 
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

    /**
     * RELASI: User belongs to Division
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * RELASI: User has many LeaveRequests
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * RELASI: User has one LeaveQuota (tahun ini)
     */
    public function leaveQuota(): HasOne
    {
        return $this->hasOne(LeaveQuota::class)->where('year', now()->year);
    }

    /**
     * RELASI: User has many LeaveQuotas (semua tahun)
     */
    public function leaveQuotas(): HasMany
    {
        return $this->hasMany(LeaveQuota::class);
    }

    /**
     * RELASI: User has many LeaveApprovals (sebagai approver)
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(LeaveApproval::class, 'approver_id');
    }

    /**
     * RELASI: Division yang dipimpin (jika user adalah leader)
     */
    public function leadingDivision(): HasOne
    {
        return $this->hasOne(Division::class, 'leader_id');
    }

    /**
     * ACCESSOR: Get role label
     */
    public function getRoleLabelAttribute(): string
    {
        return $this->role->label();
    }

    /**
     * ACCESSOR: Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        
        // Default avatar
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * SCOPE: Filter by role
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * SCOPE: Only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * SCOPE: Users without division
     */
    public function scopeWithoutDivision($query)
    {
        return $query->whereNull('division_id');
    }

    /**
     * Check if user can request leave
     */
    public function canRequestLeave(): bool
    {
        return $this->role->canRequestLeave();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    /**
     * Check if user is HRD
     */
    public function isHRD(): bool
    {
        return $this->role === UserRole::HRD;
    }

    /**
     * Check if user is leader
     */
    public function isLeader(): bool
    {
        return $this->role === UserRole::LEADER;
    }

    /**
     * Check if user is employee
     */
    public function isEmployee(): bool
    {
        return $this->role === UserRole::EMPLOYEE;
    }
}