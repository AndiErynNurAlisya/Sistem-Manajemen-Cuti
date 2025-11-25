<?php

namespace App\Http\Controllers\Employee;

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
     * Display a listing of leave requests
     */
    public function index(Request $request)
    {
        $query = LeaveRequest::where('user_id', $this->user()->id)
            ->with('approvals.approver');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by leave type
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }
         
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }
        
        // Sort
        $query->latest();
        
        $leaveRequests = $this->paginate($query);
        
        return view('employee.leave-requests.index', compact('leaveRequests'));
    }
    
    /**
     * Show the form for creating a new leave request
     */
    public function create()
    {
        // Get kuota info
        $quota = $this->quotaService->getQuota($this->user());
        
        return view('employee.leave-requests.create', compact('quota'));
    }
    
    /**
     * Store a newly created leave request
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
                'status' => 'pending',
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
        'Pengajuan cuti berhasil diajukan!',
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
        
        return view('employee.leave-requests.show', compact('leaveRequest'));
    }
    
    /**
     * Cancel leave request (hanya jika status pending)
     */
    public function cancel(Request $request, LeaveRequest $leaveRequest)
    {
        // Validasi ownership
        $this->validateOwnership($leaveRequest);
        
        // Validasi status
        if (!$leaveRequest->canBeCancelled()) {
            return $this->error('Hanya pengajuan dengan status pending yang bisa dibatalkan.');
        }
        
        // Validasi alasan
        $request->validate([
            'cancellation_reason' => ['required', 'string', 'min:10']
        ], [
            'cancellation_reason.required' => 'Alasan pembatalan wajib diisi.',
            'cancellation_reason.min' => 'Alasan minimal 10 karakter.'
        ]);
        
        return $this->transaction(function() use ($request, $leaveRequest) {
            // Update status
            $leaveRequest->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason
            ]);
            
            // Kembalikan kuota jika cuti tahunan
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
    
    /**
     * Download surat permohonan cuti
     */
    public function downloadRequestLetter(LeaveRequest $leaveRequest)
    {
        // Validasi ownership
        $this->validateOwnership($leaveRequest);
        
        if (!$leaveRequest->request_letter_pdf || !fileExists($leaveRequest->request_letter_pdf)) {
            return $this->error('Surat permohonan tidak ditemukan.');
        }
        
        $fileName = 'Surat_Permohonan_' . str_replace(' ', '', $leaveRequest->user->full_name) . '' . $leaveRequest->start_date->format('Y-m-d') . '.pdf';
        
        return \Storage::disk('public')->download($leaveRequest->request_letter_pdf, $fileName);
    }
    
    /**
     * Download surat izin cuti (setelah approved)
     */
    public function downloadApprovalLetter(LeaveRequest $leaveRequest)
    {
        // Validasi ownership
        $this->validateOwnership($leaveRequest);
        
        if ($leaveRequest->status->value !== 'approved') {
            return $this->error('Surat izin hanya tersedia untuk cuti yang sudah disetujui.');
        }
        
        if (!$leaveRequest->approval_letter_pdf || !fileExists($leaveRequest->approval_letter_pdf)) {
            return $this->error('Surat izin tidak ditemukan.');
        }
        
        $fileName = 'Surat_Izin_Cuti_' . str_replace(' ', '', $leaveRequest->user->full_name) . '' . $leaveRequest->start_date->format('Y-m-d') . '.pdf';
        
        return \Storage::disk('public')->download($leaveRequest->approval_letter_pdf, $fileName);
    }
}