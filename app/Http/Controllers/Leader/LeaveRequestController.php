<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\BaseController;
use App\Http\Requests\LeaveRequestStoreRequest;
use App\Models\LeaveRequest;
use App\Services\WorkingDayCalculator;
use App\Services\LeaveQuotaService;
use App\Services\PdfGeneratorService;
use Illuminate\Http\Request;

class LeaveRequestController extends BaseController
{
    protected $calculator;
    protected $quotaService;
    protected $pdfService;
    
    public function __construct(
        WorkingDayCalculator $calculator,
        LeaveQuotaService $quotaService,
        PdfGeneratorService $pdfService
    ) {
        $this->calculator = $calculator;
        $this->quotaService = $quotaService;
        $this->pdfService = $pdfService;
    }
    
    /**
     * Display a listing of leader's own leave requests
     */
    public function index(Request $request)
    {
        $query = LeaveRequest::where('user_id', $this->user()->id)
            ->with('approvals.approver');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $query->latest();
        
        $leaveRequests = $this->paginate($query);
        
        return view('leader.leave-requests.index', compact('leaveRequests'));
    }
    
    /**
     * Show the form for creating a new leave request
     */
    public function create()
    {
        $quota = $this->quotaService->getQuota($this->user());
        
        return view('leader.leave-requests.create', compact('quota'));
    }
    
    /**
     * Store a newly created leave request (langsung ke HRD, skip approval leader)
     */
    public function store(LeaveRequestStoreRequest $request)
    {
        return $this->transaction(function() use ($request) {
            $user = $this->user();
            
            // Hitung total hari kerja
            $totalDays = $this->calculator->calculate(
                $request->start_date,
                $request->end_date
            );
            
            // Prepare data
            $data = [
                'user_id' => $user->id,
                'leave_type' => $request->leave_type,
                'request_date' => now(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => $totalDays,
                'reason' => $request->reason,
                'address_during_leave' => $request->address_during_leave,
                'emergency_contact' => $request->emergency_contact,
                'status' => 'pending', // Langsung pending untuk HRD
            ];
            
            // Handle upload surat dokter (cuti sakit)
            if ($request->hasFile('medical_certificate')) {
                $data['medical_certificate'] = $this->uploadFile(
                    $request->file('medical_certificate'),
                    'medical_certificates'
                );
            }
            
            // Create leave request
            $leaveRequest = LeaveRequest::create($data);
            
            // Kurangi kuota jika cuti tahunan
            if ($request->leave_type === 'annual') {
                $this->quotaService->deduct($user, $totalDays);
            }
            
            // Generate surat permohonan (untuk cuti tahunan)
            if ($request->leave_type === 'annual') {
                $requestLetterPath = $this->pdfService->generateRequestLetter($leaveRequest);
                $leaveRequest->update(['request_letter_pdf' => $requestLetterPath]);
            }
            
            return $leaveRequest;
        },
        'Pengajuan cuti berhasil! Menunggu approval HRD.',
        'Gagal mengajukan cuti');
    }
    
    /**
     * Display the specified leave request
     */
    public function show(LeaveRequest $leaveRequest)
    {
        // Validasi ownership
        $this->validateOwnership($leaveRequest);
        
        $leaveRequest->load(['approvals.approver', 'user.division']);
        
        return view('leader.leave-requests.show', compact('leaveRequest'));
    }
    
    /**
     * Cancel leave request (same as employee)
     */
    public function cancel(Request $request, LeaveRequest $leaveRequest)
    {
        $this->validateOwnership($leaveRequest);
        
        if (!$leaveRequest->canBeCancelled()) {
            return $this->error('Hanya pengajuan dengan status pending yang bisa dibatalkan.');
        }
        
        $request->validate([
            'cancellation_reason' => ['required', 'string', 'min:10']
        ]);
        
        return $this->transaction(function() use ($request, $leaveRequest) {
            $leaveRequest->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason
            ]);
            
            if ($leaveRequest->leave_type->value === 'annual') {
                $this->quotaService->restore(
                    $leaveRequest->user,
                    $leaveRequest->total_days
                );
            }
            
            return $leaveRequest;
        },
        'Pengajuan cuti berhasil dibatalkan.',
        'Gagal membatalkan pengajuan cuti');
    }
}