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
    
    /**
     * Leader approve pengajuan cuti
     * 
     * @param LeaveRequest $leaveRequest
     * @param User $leader
     * @param string|null $notes
     * @return void
     * @throws \Exception
     */
    public function leaderApprove(LeaveRequest $leaveRequest, User $leader, ?string $notes = null): void
    {
        DB::beginTransaction();
        
        try {
            // Validasi status
            if ($leaveRequest->status !== LeaveStatus::PENDING) {
                throw new \Exception('Pengajuan sudah diproses sebelumnya.');
            }
            
            // Update status pengajuan
            $leaveRequest->update([
                'status' => LeaveStatus::APPROVED_BY_LEADER
            ]);
            
            // Record approval
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
    
    /**
     * Leader reject pengajuan cuti
     * 
     * @param LeaveRequest $leaveRequest
     * @param User $leader
     * @param string $reason
     * @return void
     * @throws \Exception
     */
    public function leaderReject(LeaveRequest $leaveRequest, User $leader, string $reason): void
    {
        DB::beginTransaction();
        
        try {
            // Validasi status
            if ($leaveRequest->status !== LeaveStatus::PENDING) {
                throw new \Exception('Pengajuan sudah diproses sebelumnya.');
            }
            
            // Update status pengajuan
            $leaveRequest->update([
                'status' => LeaveStatus::REJECTED,
                'rejection_note' => $reason
            ]);
            
            // Record approval
            LeaveApproval::create([
                'leave_request_id' => $leaveRequest->id,
                'approver_id' => $leader->id,
                'approver_role' => 'leader',
                'status' => 'rejected',
                'notes' => $reason,
                'approved_at' => now()
            ]);
            
            // Kembalikan kuota jika cuti tahunan
            if ($leaveRequest->leave_type->value === 'annual') {
                $this->quotaService->restore(
                    $leaveRequest->user,
                    $leaveRequest->total_days
                );
            }
            
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
    
    /**
     * HRD final approve pengajuan cuti
     * 
     * @param LeaveRequest $leaveRequest
     * @param User $hrd
     * @param string|null $notes
     * @return void
     * @throws \Exception
     */
    public function hrdApprove(LeaveRequest $leaveRequest, User $hrd, ?string $notes = null): void
    {
        DB::beginTransaction();
        
        try {
            // Validasi status (harus pending atau approved_by_leader)
            $validStatuses = [LeaveStatus::PENDING, LeaveStatus::APPROVED_BY_LEADER];
            if (!in_array($leaveRequest->status, $validStatuses)) {
                throw new \Exception('Pengajuan sudah diproses sebelumnya.');
            }
            
            // Validasi kuota sekali lagi untuk cuti tahunan
            if ($leaveRequest->leave_type->value === 'annual') {
                if (!$this->quotaService->isAvailable($leaveRequest->user, $leaveRequest->total_days)) {
                    throw new \Exception('Kuota tidak mencukupi saat final approval.');
                }
            }
            
            // Update status pengajuan
            $leaveRequest->update([
                'status' => LeaveStatus::APPROVED,
                'approved_at' => now()
            ]);
            
            // Record approval
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
                'hrd_id' => $hrd->id
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * HRD reject pengajuan cuti
     * 
     * @param LeaveRequest $leaveRequest
     * @param User $hrd
     * @param string $reason
     * @return void
     * @throws \Exception
     */
    public function hrdReject(LeaveRequest $leaveRequest, User $hrd, string $reason): void
    {
        DB::beginTransaction();
        
        try {
            // Validasi status
            $validStatuses = [LeaveStatus::PENDING, LeaveStatus::APPROVED_BY_LEADER];
            if (!in_array($leaveRequest->status, $validStatuses)) {
                throw new \Exception('Pengajuan sudah diproses sebelumnya.');
            }
            
            // Update status pengajuan
            $leaveRequest->update([
                'status' => LeaveStatus::REJECTED,
                'rejection_note' => $reason
            ]);
            
            // Record approval
            LeaveApproval::create([
                'leave_request_id' => $leaveRequest->id,
                'approver_id' => $hrd->id,
                'approver_role' => 'hrd',
                'status' => 'rejected',
                'notes' => $reason,
                'approved_at' => now()
            ]);
            
            // Kembalikan kuota jika cuti tahunan
            if ($leaveRequest->leave_type->value === 'annual') {
                $this->quotaService->restore(
                    $leaveRequest->user,
                    $leaveRequest->total_days
                );
            }
            
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
    
    /**
     * Batch approve multiple requests (untuk HRD)
     * 
     * @param array $leaveRequestIds
     * @param User $hrd
     * @param string|null $notes
     * @return array
     */
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
    
    /**
     * Get approval timeline untuk ditampilkan
     * 
     * @param LeaveRequest $leaveRequest
     * @return array
     */
    public function getTimeline(LeaveRequest $leaveRequest): array
    {
        $timeline = [];
        
        // 1. Pengajuan
        $timeline[] = [
            'type' => 'submitted',
            'label' => 'Diajukan',
            'date' => $leaveRequest->created_at,
            'user' => $leaveRequest->user,
            'notes' => null
        ];
        
        // 2. Approvals
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
        
        // 3. Cancellation (jika ada)
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