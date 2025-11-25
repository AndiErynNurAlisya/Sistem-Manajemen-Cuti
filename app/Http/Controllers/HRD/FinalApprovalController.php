<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\BaseController;
use App\Http\Requests\ApprovalRequest;
use App\Models\LeaveRequest;
use App\Services\LeaveApprovalService;
use App\Services\PdfGeneratorService;
use Illuminate\Http\Request;

class FinalApprovalController extends BaseController
{
    protected $approvalService;
    protected $pdfService;
    
    public function __construct(
        LeaveApprovalService $approvalService,
        PdfGeneratorService $pdfService
    ) {
        $this->approvalService = $approvalService;
        $this->pdfService = $pdfService;
    }
    
    /**
     * Display a listing of requests pending final approval
     */
    public function index(Request $request)
    {
        // Get pengajuan yang butuh final approval HRD
        // Status: 'pending' (dari leader) atau 'approved_by_leader' (dari employee)
        $query = LeaveRequest::whereIn('status', ['pending', 'approved_by_leader'])
            ->with(['user', 'user.division', 'approvals.approver']);
        
        // Filter by leave type
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }
        
        // Filter by division
        if ($request->filled('division_id')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }
        
        // Search by employee name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        // Sort
        $query->latest('created_at');
        
        $pendingApprovals = $this->paginate($query);
        
        // Get divisions for filter
        $divisions = \App\Models\Division::all();
        
        return view('hrd.final-approvals.index', compact('pendingApprovals', 'divisions'));
    }
    
    /**
     * Display the specified leave request for final approval
     */
    public function show(LeaveRequest $leaveRequest)
    {
        // Validasi: status harus pending atau approved_by_leader
        if (!in_array($leaveRequest->status->value, ['pending', 'approved_by_leader'])) {
            return $this->error('Pengajuan ini sudah diproses sebelumnya.', 'hrd.final-approvals.index');
        }
        
        $leaveRequest->load([
            'user',
            'user.division',
            'user.leaveQuota',
            'approvals.approver'
        ]);
        
        // Get approval timeline
        $timeline = $this->approvalService->getTimeline($leaveRequest);
        
        return view('hrd.final-approvals.show', compact('leaveRequest', 'timeline'));
    }
    
    /**
     * Final approve leave request
     */
    public function approve(ApprovalRequest $request, LeaveRequest $leaveRequest)
    {
        return $this->transaction(function() use ($request, $leaveRequest) {
            $user = $this->user();
            
            // Final approve
            $this->approvalService->hrdApprove(
                $leaveRequest,
                $user,
                $request->notes
            );
            
            // Generate surat izin cuti (approval letter)
            $approvalLetterPath = $this->pdfService->generateApprovalLetter($leaveRequest);
            $leaveRequest->update(['approval_letter_pdf' => $approvalLetterPath]);
            
            return $leaveRequest;
        },
        'Pengajuan cuti berhasil disetujui! Surat izin telah digenerate.',
        'Gagal menyetujui pengajuan cuti');
    }
    
    /**
     * Final reject leave request
     */
    public function reject(ApprovalRequest $request, LeaveRequest $leaveRequest)
    {
        return $this->transaction(function() use ($request, $leaveRequest) {
            $user = $this->user();
            
            // Final reject
            $this->approvalService->hrdReject(
                $leaveRequest,
                $user,
                $request->notes
            );
            
            return $leaveRequest;
        },
        'Pengajuan cuti telah ditolak.',
        'Gagal menolak pengajuan cuti');
    }
    
    /**
     * Batch approve multiple requests
     */
    public function batchApprove(Request $request)
    {
        $request->validate([
            'leave_request_ids' => ['required', 'array'],
            'leave_request_ids.*' => ['exists:leave_requests,id'],
            'notes' => ['nullable', 'string', 'max:500']
        ]);
        
        return $this->transaction(function() use ($request) {
            $user = $this->user();
            
            $results = $this->approvalService->batchApprove(
                $request->leave_request_ids,
                $user,
                $request->notes
            );
            
            // Generate PDF untuk yang berhasil
            foreach ($results['success'] as $id) {
                $leaveRequest = LeaveRequest::find($id);
                if ($leaveRequest) {
                    $approvalLetterPath = $this->pdfService->generateApprovalLetter($leaveRequest);
                    $leaveRequest->update(['approval_letter_pdf' => $approvalLetterPath]);
                }
            }
            
            return $results;
        },
        count($request->leave_request_ids) . ' pengajuan berhasil diproses!',
        'Gagal memproses batch approval');
    }
}