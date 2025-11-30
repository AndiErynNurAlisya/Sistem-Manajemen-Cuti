<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\BaseController;
use App\Http\Requests\LeaveRequestStoreRequest;
use App\Models\LeaveRequest;
use App\Services\WorkingDayCalculator;
use App\Services\LeaveQuotaService;
use App\Services\PdfGeneratorService;
use Illuminate\Http\Request;
use App\Enums\LeaveType; 

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
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('leave_type')) {
            $query->where('leave_type', LeaveType::from($request->leave_type));
        }

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }
        
        $query->latest();
        
        $leaveRequests = $this->paginate($query);
        
        return view('employee.leave-requests.index', compact('leaveRequests'));
    }
    

    /**
     * Show the form for creating a new leave request
     */
    public function create()
    {
        $user = $this->user();
        
        $quota = $this->quotaService->getQuota($user);
        
        $canRequestAnnual = canRequestAnnualLeave($user);
        $monthsOfService = getMonthsOfService($user);
        $remainingMonths = getRemainingMonthsToEligible($user);
        
        return view('employee.leave-requests.create', compact(
            'quota', 
            'canRequestAnnual', 
            'monthsOfService', 
            'remainingMonths'
        ));
    }
    

    /**
     * Store a newly created leave request
     */
    public function store(LeaveRequestStoreRequest $request)
    {
        return $this->transaction(function() use ($request) {

            $user = $this->user();

            if (!$user->division_id) {
                return back()->withErrors([
                    'division' => 'Anda belum terdaftar di divisi manapun.'
                ])->withInput();
            }

            $leaveType = LeaveType::from($request->leave_type);

            $totalDays = $this->calculator->calculate(
                $request->start_date,
                $request->end_date
            );

            $data = [
                'user_id' => $user->id,
                'leave_type' => $leaveType,    // ⬅ ENUM
                'request_date' => now(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => $totalDays,
                'reason' => $request->reason,
                'address_during_leave' => $request->address_during_leave,
                'emergency_contact' => $request->emergency_contact,
                'status' => 'pending',
            ];

            // buth surat dokter (khusus cuti sakit)
            if ($request->hasFile('medical_certificate')) {
                $data['medical_certificate'] = $this->uploadFile(
                    $request->file('medical_certificate'),
                    'medical_certificates'
                );
            }

            $leaveRequest = LeaveRequest::create($data);

            if ($leaveType === LeaveType::ANNUAL) {
                $requestLetterPath = $this->pdfService->generateRequestLetter($leaveRequest);
                $leaveRequest->update(['request_letter_pdf' => $requestLetterPath]);
            }

            return $leaveRequest;
        },
        'Pengajuan cuti berhasil diajukan!',
        'Gagal mengajukan cuti');
    }
    

    /**
     * Show leave request
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $this->validateOwnership($leaveRequest);
        
        $leaveRequest->load(['approvals.approver', 'user.division']);
        
        return view('employee.leave-requests.show', compact('leaveRequest'));
    }
    

    /**
     * Cancel leave request
     */
    public function cancel(Request $request, LeaveRequest $leaveRequest)
    {
        $this->validateOwnership($leaveRequest);

        if (!$leaveRequest->canBeCancelled()) {
            return $this->error('Hanya pengajuan dengan status pending.');
        }

        $request->validate([
            'cancellation_reason' => ['required', 'string', 'min:10']
        ]);

        return $this->transaction(function() use ($request, $leaveRequest) {

            $leaveRequest->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason
            ]);

            return $leaveRequest;

        }, 'Pengajuan cuti berhasil dibatalkan.', 'Gagal membatalkan');
    }
    

    /**
     * Download request letter
     */
    public function downloadRequestLetter(LeaveRequest $leaveRequest)
    {
        $this->validateOwnership($leaveRequest);
        
        if (!$leaveRequest->request_letter_pdf || !fileExists($leaveRequest->request_letter_pdf)) {
            return $this->error('Surat permohonan tidak ditemukan.');
        }
        
        $fileName = 'Surat_Permohonan_' . str_replace(' ', '', $leaveRequest->user->full_name) . '_' . $leaveRequest->start_date->format('Y-m-d') . '.pdf';
        
        return \Storage::disk('public')->download($leaveRequest->request_letter_pdf, $fileName);
    }
    

    /**
     * Download approval letter
     */
    public function downloadApprovalLetter(LeaveRequest $leaveRequest)
    {
        $this->validateOwnership($leaveRequest);
        
        if ($leaveRequest->status->value !== 'approved') {
            return $this->error('Surat izin hanya tersedia untuk cuti yang sudah disetujui.');
        }
        
        if (!$leaveRequest->approval_letter_pdf || !fileExists($leaveRequest->approval_letter_pdf)) {
            return $this->error('Surat izin tidak ditemukan.');
        }
        
        $fileName = 'Surat_Izin_Cuti_' . str_replace(' ', '', $leaveRequest->user->full_name) . '_' . $leaveRequest->start_date->format('Y-m-d') . '.pdf';
        
        return \Storage::disk('public')->download($leaveRequest->approval_letter_pdf, $fileName);
    }
}
