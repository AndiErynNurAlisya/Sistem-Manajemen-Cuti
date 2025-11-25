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

    /**
     * RELASI: LeaveRequest belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELASI: LeaveRequest has many LeaveApprovals
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(LeaveApproval::class)->orderBy('created_at');
    }

    /**
     * RELASI: Get leader approval
     */
    public function leaderApproval(): HasMany
    {
        return $this->hasMany(LeaveApproval::class)->where('approver_role', 'leader');
    }

    /**
     * RELASI: Get HRD approval
     */
    public function hrdApproval(): HasMany
    {
        return $this->hasMany(LeaveApproval::class)->where('approver_role', 'hrd');
    }

    /**
     * ACCESSOR: Get medical certificate URL
     */
    public function getMedicalCertificateUrlAttribute(): ?string
    {
        return $this->medical_certificate 
            ? asset('storage/' . $this->medical_certificate) 
            : null;
    }

    /**
     * ACCESSOR: Get request letter URL
     */
    public function getRequestLetterUrlAttribute(): ?string
    {
        return $this->request_letter_pdf 
            ? asset('storage/' . $this->request_letter_pdf) 
            : null;
    }

    /**
     * ACCESSOR: Get approval letter URL
     */
    public function getApprovalLetterUrlAttribute(): ?string
    {
        return $this->approval_letter_pdf 
            ? asset('storage/' . $this->approval_letter_pdf) 
            : null;
    }

    /**
     * ACCESSOR: Format periode
     */
    public function getPeriodAttribute(): string
    {
        return $this->start_date->format('d M Y') . ' - ' . $this->end_date->format('d M Y');
    }

    /**
     * Check if can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return $this->status->canBeCancelled();
    }

    /**
     * Check if needs leader approval
     */
    public function needsLeaderApproval(): bool
    {
        return $this->status === LeaveStatus::PENDING && 
               $this->user->role->value !== 'leader';
    }

    /**
     * Check if needs HRD approval
     */
    public function needsHRDApproval(): bool
    {
        return in_array($this->status, [
            LeaveStatus::PENDING,  // Dari leader
            LeaveStatus::APPROVED_BY_LEADER  // Dari employee
        ]);
    }

    /**
     * SCOPE: Filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * SCOPE: Filter by leave type
     */
    public function scopeType($query, $type)
    {
        return $query->where('leave_type', $type);
    }

    /**
     * SCOPE: Pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * SCOPE: Approved by leader
     */
    public function scopeApprovedByLeader($query)
    {
        return $query->where('status', 'approved_by_leader');
    }

    /**
     * SCOPE: Final approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * SCOPE: Current month
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('start_date', now()->month)
                     ->whereYear('start_date', now()->year);
    }

    /**
     * SCOPE: This week
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('start_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }
}