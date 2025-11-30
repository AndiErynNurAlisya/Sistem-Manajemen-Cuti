<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\LeaveApproval;
use App\Models\User;
use App\Enums\LeaveStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveApprovalService
{
    protected $quotaService;
    
    public function __construct(LeaveQuotaService $quotaService)
    {
        $this->quotaService = $quotaService;
    }
    
    public function leaderApprove(LeaveRequest $leaveRequest, User $leader, ?string $notes = null): void
    {
        DB::beginTransaction();
        
        try {
            if ($leaveRequest->status !== LeaveStatus::PENDING) {
                throw new \Exception('Pengajuan sudah diproses sebelumnya.');
            }
            
            $leaveRequest->update([
                'status' => LeaveStatus::APPROVED_BY_LEADER
            ]);
            
            LeaveApproval::create([
                'leave_request_id' => $leaveRequest->id,
                'approver_id' => $leader->id,
                'approver_role' => 'leader',
                'status' => 'approved',
                'notes' => $notes,
                'approved_at' => now()
            ]);
            
            DB::commit();
            
            Log::info('Leader approved leave request', [
                'leave_request_id' => $leaveRequest->id,
                'leader_id' => $leader->id
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function leaderReject(LeaveRequest $leaveRequest, User $leader, string $reason): void
    {
        DB::beginTransaction();
        
        try {
            if ($leaveRequest->status !== LeaveStatus::PENDING) {
                throw new \Exception('Pengajuan sudah diproses sebelumnya.');
            }
            
            $leaveRequest->update([
                'status' => LeaveStatus::REJECTED,
                'rejection_note' => $reason
            ]);
            
            LeaveApproval::create([
                'leave_request_id' => $leaveRequest->id,
                'approver_id' => $leader->id,
                'approver_role' => 'leader',
                'status' => 'rejected',
                'notes' => $reason,
                'approved_at' => now()
            ]);
            
            DB::commit();
            
            Log::info('Leader rejected leave request', [
                'leave_request_id' => $leaveRequest->id,
                'leader_id' => $leader->id
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function hrdApprove(LeaveRequest $leaveRequest, User $hrd, ?string $notes = null): void
    {
        DB::beginTransaction();
        
        try {
            $validStatuses = [LeaveStatus::PENDING, LeaveStatus::APPROVED_BY_LEADER];
            if (!in_array($leaveRequest->status, $validStatuses)) {
                throw new \Exception('Pengajuan sudah diproses sebelumnya.');
            }
            
            if ($leaveRequest->leave_type->value === 'annual') {
                if (!$this->quotaService->isAvailable($leaveRequest->user, $leaveRequest->total_days)) {
                    throw new \Exception('Kuota tidak mencukupi. Sisa kuota tidak cukup untuk approve pengajuan ini.');
                }
                
                $this->quotaService->deduct($leaveRequest->user, $leaveRequest->total_days);
            }
            
            $leaveRequest->update([
                'status' => LeaveStatus::APPROVED,
                'approved_at' => now()
            ]);
            
            LeaveApproval::create([
                'leave_request_id' => $leaveRequest->id,
                'approver_id' => $hrd->id,
                'approver_role' => 'hrd',
                'status' => 'approved',
                'notes' => $notes,
                'approved_at' => now()
            ]);
            
            DB::commit();
            
            Log::info('HRD approved leave request', [
                'leave_request_id' => $leaveRequest->id,
                'hrd_id' => $hrd->id,
                'quota_deducted' => $leaveRequest->leave_type->value === 'annual' ? $leaveRequest->total_days : 0
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function hrdReject(LeaveRequest $leaveRequest, User $hrd, string $reason): void
    {
        DB::beginTransaction();
        
        try {
            $validStatuses = [LeaveStatus::PENDING, LeaveStatus::APPROVED_BY_LEADER];
            if (!in_array($leaveRequest->status, $validStatuses)) {
                throw new \Exception('Pengajuan sudah diproses sebelumnya.');
            }
            
            $leaveRequest->update([
                'status' => LeaveStatus::REJECTED,
                'rejection_note' => $reason
            ]);
            
            LeaveApproval::create([
                'leave_request_id' => $leaveRequest->id,
                'approver_id' => $hrd->id,
                'approver_role' => 'hrd',
                'status' => 'rejected',
                'notes' => $reason,
                'approved_at' => now()
            ]);
            
            DB::commit();
            
            Log::info('HRD rejected leave request', [
                'leave_request_id' => $leaveRequest->id,
                'hrd_id' => $hrd->id
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function batchApprove(array $leaveRequestIds, User $hrd, ?string $notes = null): array
    {
        $results = [
            'success' => [],
            'failed' => []
        ];
        
        foreach ($leaveRequestIds as $id) {
            try {
                $leaveRequest = LeaveRequest::findOrFail($id);
                $this->hrdApprove($leaveRequest, $hrd, $notes);
                $results['success'][] = $id;
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'id' => $id,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $results;
    }
    
    public function batchReject(array $leaveRequestIds, User $hrd, ?string $notes = null): array
    {
        $results = [
            'success' => [],
            'failed' => []
        ];

        foreach ($leaveRequestIds as $id) {
            try {
                $leaveRequest = LeaveRequest::findOrFail($id);
                $this->hrdReject($leaveRequest, $hrd, $notes);
                $results['success'][] = $id;
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'id' => $id,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }
    
    public function getTimeline(LeaveRequest $leaveRequest): array
    {
        $timeline = [];
        
        $timeline[] = [
            'type' => 'submitted',
            'label' => 'Diajukan',
            'date' => $leaveRequest->created_at,
            'user' => $leaveRequest->user,
            'notes' => null
        ];
        
        foreach ($leaveRequest->approvals as $approval) {
            $timeline[] = [
                'type' => $approval->status,
                'label' => $approval->status === 'approved' 
                    ? 'Disetujui oleh ' . ucfirst($approval->approver_role)
                    : 'Ditolak oleh ' . ucfirst($approval->approver_role),
                'date' => $approval->approved_at,
                'user' => $approval->approver,
                'notes' => $approval->notes
            ];
        }
        
        if ($leaveRequest->status === LeaveStatus::CANCELLED) {
            $timeline[] = [
                'type' => 'cancelled',
                'label' => 'Dibatalkan',
                'date' => $leaveRequest->updated_at,
                'user' => $leaveRequest->user,
                'notes' => $leaveRequest->cancellation_reason
            ];
        }
        
        return $timeline;
    }
}
