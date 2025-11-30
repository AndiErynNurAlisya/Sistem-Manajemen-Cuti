<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\LeaveType;
use App\Enums\LeaveStatus;

class LeaveRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'leave_type',
        'request_date',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'address_during_leave',
        'emergency_contact',
        'medical_certificate',
        'request_letter_pdf',
        'approval_letter_pdf',
        'status',
        'rejection_note',
        'cancellation_reason',
        'approved_at',
    ];

    protected $casts = [
        'leave_type' => LeaveType::class,
        'status' => LeaveStatus::class,
        'request_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'total_days' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(LeaveApproval::class)->orderBy('created_at');
    }

    public function leaderApproval(): HasMany
    {
        return $this->hasMany(LeaveApproval::class)->where('approver_role', 'leader');
    }

    public function hrdApproval(): HasMany
    {
        return $this->hasMany(LeaveApproval::class)->where('approver_role', 'hrd');
    }

    public function getMedicalCertificateUrlAttribute(): ?string
    {
        return $this->medical_certificate 
            ? asset('storage/' . $this->medical_certificate) 
            : null;
    }

    public function getRequestLetterUrlAttribute(): ?string
    {
        return $this->request_letter_pdf 
            ? asset('storage/' . $this->request_letter_pdf) 
            : null;
    }

    public function getApprovalLetterUrlAttribute(): ?string
    {
        return $this->approval_letter_pdf 
            ? asset('storage/' . $this->approval_letter_pdf) 
            : null;
    }

    public function getPeriodAttribute(): string
    {
        return $this->start_date->format('d M Y') . ' - ' . $this->end_date->format('d M Y');
    }

    public function canBeCancelled(): bool
    {
        return $this->status->canBeCancelled();
    }

    public function needsLeaderApproval(): bool
    {
        return $this->status === LeaveStatus::PENDING && 
               $this->user->role->value !== 'leader';
    }

    public function needsHRDApproval(): bool
    {
        return in_array($this->status, [
            LeaveStatus::PENDING,
            LeaveStatus::APPROVED_BY_LEADER
        ]);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeType($query, $type)
    {
        return $query->where('leave_type', $type);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApprovedByLeader($query)
    {
        return $query->where('status', 'approved_by_leader');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('start_date', now()->month)
                     ->whereYear('start_date', now()->year);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('start_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }
}
