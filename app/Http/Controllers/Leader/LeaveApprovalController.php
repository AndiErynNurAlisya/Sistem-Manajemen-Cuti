<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\BaseController;
use App\Http\Requests\ApprovalRequest;
use App\Models\LeaveRequest;
use App\Services\LeaveApprovalService;
use Illuminate\Http\Request;
use App\Enums\LeaveStatus;

class LeaveApprovalController extends BaseController
{
    protected $approvalService;
    
    public function __construct(LeaveApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }
    
    /**
     * Display a listing of pending approvals
     */
    public function index(Request $request)
    {
        $user = $this->user();
        $division = $user->division;
        
        if (!$division) {
            return $this->error('Anda belum terdaftar di divisi manapun.', 'leader.dashboard');
        }
        
        // Get member IDs dari divisi (exclude leader sendiri)
        $memberIds = $division->members()
            ->where('id', '!=', $user->id)
            ->pluck('id')
            ->toArray();
        
        $query = LeaveRequest::whereIn('user_id', $memberIds)
            ->where('status', LeaveStatus::PENDING->value) // 
            ->with(['user', 'user.division']);
        
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }
    
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        $query->latest('created_at');
        
        $pendingLeaves = $this->paginate($query);
        
        return view('leader.approvals.index', compact('pendingLeaves'));
    }
    
    /**
     * Display the specified leave request for approval
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $user = $this->user();
        
        if ($leaveRequest->user->division_id !== $user->division_id) {
            abort(403, 'Anda tidak memiliki akses untuk meng-approve pengajuan ini.');
        }
        
        if ($leaveRequest->status !== LeaveStatus::PENDING) {
            return $this->success('Pengajuan ini sudah berhasil disetujui.', 'leader.approvals.index');
        }
        
        $leaveRequest->load(['user', 'user.division', 'user.leaveQuota']);
        
        return view('leader.approvals.show', compact('leaveRequest'));
    }
    
    /**
     * Approve leave request
     */
    public function approve(ApprovalRequest $request, LeaveRequest $leaveRequest)
    {
        $user = $this->user();
        
        if ($leaveRequest->user->division_id !== $user->division_id) {
            abort(403, 'Anda tidak memiliki akses untuk meng-approve pengajuan ini.');
        }
        
        return $this->transaction(function() use ($request, $leaveRequest, $user) {
            $this->approvalService->leaderApprove(
                $leaveRequest,
                $user,
                $request->notes
            );
            
            return $leaveRequest;
        },
        'Pengajuan cuti berhasil disetujui! Menunggu approval HRD.',
        'Gagal menyetujui pengajuan cuti');
    }
    
    /**
     * Reject leave request
     */
    public function reject(ApprovalRequest $request, LeaveRequest $leaveRequest)
    {
        $user = $this->user();
        
        if ($leaveRequest->user->division_id !== $user->division_id) {
            abort(403, 'Anda tidak memiliki akses untuk menolak pengajuan ini.');
        }
        
        return $this->transaction(function() use ($request, $leaveRequest, $user) {
            $this->approvalService->leaderReject(
                $leaveRequest,
                $user,
                $request->notes
            );
            
            return $leaveRequest;
        },
        'Pengajuan cuti telah ditolak.',
        'Gagal menolak pengajuan cuti');
    }
}
