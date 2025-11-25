<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\BaseController;
use App\Models\LeaveRequest;
use App\Services\LeaveQuotaService;

class DashboardController extends BaseController
{
    protected $quotaService;
    
    public function __construct(LeaveQuotaService $quotaService)
    {
        $this->quotaService = $quotaService;
    }
    
    /**
     * Display employee dashboard
     */
    public function index()
    {
        $user = $this->user();
        
        // Get kuota cuti
        $quotaSummary = $this->quotaService->getSummary($user);
        
        // Jumlah cuti sakit yang diajukan (tahun ini)
        $totalSickLeaves = LeaveRequest::where('user_id', $user->id)
            ->where('leave_type', 'sick')
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Total pengajuan cuti (semua status)
        $totalLeaveRequests = LeaveRequest::where('user_id', $user->id)->count();
        
        // Pengajuan cuti terakhir (5 terbaru)
        $recentLeaves = LeaveRequest::where('user_id', $user->id)
            ->with('approvals.approver')
            ->latest()
            ->take(5)
            ->get();
        
        // Pengajuan pending
        $pendingLeaves = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
        
        return view('employee.dashboard', compact(
            'quotaSummary',
            'totalSickLeaves',
            'totalLeaveRequests',
            'recentLeaves',
            'pendingLeaves'
        ));
    }
}