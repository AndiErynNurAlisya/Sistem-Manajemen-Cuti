<?php

namespace App\Services;

use App\Models\LeaveRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PdfGeneratorService
{

    public function generateRequestLetter(LeaveRequest $leaveRequest): string
    {
        $data = $this->prepareRequestLetterData($leaveRequest);
        
        $pdf = Pdf::loadView('pdf.leave-request-letter', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);
        
        $fileName = $this->generateRequestFileName($leaveRequest);
        $filePath = 'leave_requests/' . $fileName;
        
        Storage::disk('public')->put($filePath, $pdf->output());
        
        Log::info('Request letter generated', [
            'leave_request_id' => $leaveRequest->id,
            'file_path' => $filePath
        ]);
        
        return $filePath;
    }
    

    public function generateApprovalLetter(LeaveRequest $leaveRequest): string
    {
        $data = $this->prepareApprovalLetterData($leaveRequest);
        
        $pdf = Pdf::loadView('pdf.leave-approval-letter', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);
        
        $fileName = $this->generateApprovalFileName($leaveRequest);
        $filePath = 'leave_approvals/' . $fileName;
        
        Storage::disk('public')->put($filePath, $pdf->output());
        
        Log::info('Approval letter generated', [
            'leave_request_id' => $leaveRequest->id,
            'file_path' => $filePath
        ]);
        
        return $filePath;
    }
    

    protected function prepareRequestLetterData(LeaveRequest $leaveRequest): array
    {
        return [
            'requestId' => 'REQ-' . str_pad($leaveRequest->id, 5, '0', STR_PAD_LEFT),
            'employeeName' => $leaveRequest->user->full_name,
            'division' => $leaveRequest->user->division?->name ?? '-',
            'leaveType' => $leaveRequest->leave_type->label(),
            'startDate' => $this->formatDate($leaveRequest->start_date),
            'endDate' => $this->formatDate($leaveRequest->end_date),
            'totalDays' => $leaveRequest->total_days,
            'reason' => $leaveRequest->reason,
            'address' => $leaveRequest->address_during_leave,
            'emergencyContact' => $leaveRequest->emergency_contact,
            'city' => config('app.company.city', 'Bali'),
            'submissionDate' => $this->formatDate($leaveRequest->request_date),
            'timestamp' => now()->format('d M Y H:i:s'),
        ];
    }
    

   protected function prepareApprovalLetterData(LeaveRequest $leaveRequest): array
{

    $hrdApproval = $leaveRequest->approvals()
        ->where('approver_role', 'hrd')
        ->where('status', 'approved')
        ->first();
    
    return [
        'letterNumber' => $this->generateLetterNumber($leaveRequest),
        'employeeName' => $leaveRequest->user->full_name,
        'division' => $leaveRequest->user->division?->name ?? '-',
        'leaveType' => $leaveRequest->leave_type->label(),
        'startDate' => $this->formatDate($leaveRequest->start_date),
        'endDate' => $this->formatDate($leaveRequest->end_date),
        'totalDays' => $leaveRequest->total_days,
        'reason' => $leaveRequest->reason,
        'address' => $leaveRequest->address_during_leave,
        'emergencyContact' => $leaveRequest->emergency_contact,
        'hrdName' => $hrdApproval?->approver->full_name ?? 'HRD Manager',
        'city' => config('app.company.city', 'Bali'),
        'approvalDate' => $this->formatDate($leaveRequest->approved_at ?? now()),
        'companyName' => config('app.company.name', 'Cafe Kita'),
        'companyAddress' => config('app.company.address', 'Bali, Indonesia'),
        'companyPhone' => config('app.company.phone', '(062) 12345678'),
        'companyEmail' => config('app.company.email', 'cafekita@ngafe.com'),
    ];
}
    

    protected function generateLetterNumber(LeaveRequest $leaveRequest): string
    {
        $sequence = LeaveRequest::where('status', 'approved')
            ->whereYear('approved_at', now()->year)
            ->whereNotNull('approved_at')
            ->count();
        
        $monthRoman = $this->getMonthRoman(now()->month);
        
        return sprintf(
            '%03d/CUTI/HRD/%s/%d',
            $sequence,
            $monthRoman,
            now()->year
        );
    }

    protected function generateRequestFileName(LeaveRequest $leaveRequest): string
    {
        $userName = str_replace(' ', '_', strtolower($leaveRequest->user->full_name));
        $timestamp = time();
        
        return "surat_permohonan_{$userName}_{$timestamp}.pdf";
    }
    
    protected function generateApprovalFileName(LeaveRequest $leaveRequest): string
    {
        $userName = str_replace(' ', '_', strtolower($leaveRequest->user->full_name));
        $timestamp = time();
        
        return "surat_izin_{$userName}_{$timestamp}.pdf";
    }
    

    protected function formatDate($date): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $date = \Carbon\Carbon::parse($date);
        return $date->day . ' ' . $months[$date->month] . ' ' . $date->year;
    }
    

    protected function getMonthRoman(int $month): string
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        
        return $romans[$month] ?? 'I';
    }
}