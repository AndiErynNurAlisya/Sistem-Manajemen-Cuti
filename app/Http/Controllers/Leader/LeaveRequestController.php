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
    
    public function index(Request $request)
    {
        $query = LeaveRequest::where('user_id', $this->user()->id)
            ->with('approvals.approver');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $query->latest();

        $leaveRequests = $this->paginate($query);

        return view('leader.leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        $quota = $this->quotaService->getQuota($this->user());
        
        return view('leader.leave-requests.create', compact('quota'));
    }

    public function store(LeaveRequestStoreRequest $request)
    {
        return $this->transaction(function() use ($request) {
            $user = $this->user();
            
            $totalDays = $this->calculator->calculate(
                $request->start_date,
                $request->end_date
            );
            
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
            
            if ($request->hasFile('medical_certificate')) {
                $data['medical_certificate'] = $this->uploadFile(
                    $request->file('medical_certificate'),
                    'medical_certificates'
                );
            }
            
            $leaveRequest = LeaveRequest::create($data);
            
            if ($request->leave_type === 'annual') {
                $this->quotaService->deduct($user, $totalDays);
            }
            
            if ($request->leave_type === 'annual') {
                $requestLetterPath = $this->pdfService->generateRequestLetter($leaveRequest);
                $leaveRequest->update(['request_letter_pdf' => $requestLetterPath]);
            }
            
            return $leaveRequest;
        },
        'Pengajuan cuti berhasil! Menunggu approval HRD.',
        'Gagal mengajukan cuti');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $this->validateOwnership($leaveRequest);
        
        $leaveRequest->load(['approvals.approver', 'user.division']);
        
        return view('leader.leave-requests.show', compact('leaveRequest'));
    }

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

    public function downloadRequest(LeaveRequest $leaveRequest)
    {
        $this->validateOwnership($leaveRequest);
        
        if (!$leaveRequest->request_letter_pdf || !\Illuminate\Support\Facades\Storage::disk('public')->exists($leaveRequest->request_letter_pdf)) {
            return $this->error('Surat permohonan tidak ditemukan.');
        }
        
        $fileName = 'Surat_Permohonan_' . str_replace(' ', '', $leaveRequest->user->full_name) . '_' . $leaveRequest->start_date->format('Y-m-d') . '.pdf';
        
        return \Storage::disk('public')->download($leaveRequest->request_letter_pdf, $fileName);
    }

    public function downloadApproval(LeaveRequest $leaveRequest)
    {
        $this->validateOwnership($leaveRequest);
        
        if ($leaveRequest->status !== 'approved') {
            return $this->error('Surat izin hanya tersedia untuk cuti yang sudah disetujui.');
        }
        
        if (!$leaveRequest->approval_letter_pdf || !\Illuminate\Support\Facades\Storage::disk('public')->exists($leaveRequest->approval_letter_pdf)) {
            return $this->error('Surat izin tidak ditemukan.');
        }
        
        $fileName = 'Surat_Izin_Cuti_' . str_replace(' ', '', $leaveRequest->user->full_name) . '_' . $leaveRequest->start_date->format('Y-m-d') . '.pdf';
        
        return \Storage::disk('public')->download($leaveRequest->approval_letter_pdf, $fileName);
    }
}
