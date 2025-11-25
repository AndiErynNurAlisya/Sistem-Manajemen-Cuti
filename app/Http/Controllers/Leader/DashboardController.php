<?php

namespace App\Http\Controllers\Leader;

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
     * Display leader dashboard
     */
    public function index()
    {
        $user = $this->user();
        $division = $user->division;
        
        if (!$division) {
            return $this->error('Anda belum terdaftar di divisi manapun.', 'employee.dashboard');
        }
        
        // Get member IDs dari divisi
        $memberIds = $division->members->pluck('id')->toArray();
        
        // Total pengajuan cuti masuk dari anggota
        $totalIncomingLeaves = LeaveRequest::whereIn('user_id', $memberIds)
            ->where('user_id', '!=', $user->id) // Exclude cuti leader sendiri
            ->count();
        
        // Pengajuan pending verifikasi
        $pendingApprovals = LeaveRequest::whereIn('user_id', $memberIds)
            ->where('user_id', '!=', $user->id)
            ->where('status', 'pending')
            ->count();
        
        // Kuota cuti leader sendiri
        $quotaSummary = $this->quotaService->getSummary($user);
        
        // Anggota yang sedang cuti minggu ini
        $membersOnLeaveThisWeek = LeaveRequest::whereIn('user_id', $memberIds)
            ->where('status', 'approved')
            ->where('start_date', '<=', now()->endOfWeek())
            ->where('end_date', '>=', now()->startOfWeek())
            ->with('user')
            ->get();
        
        // Pengajuan yang butuh approval (pending)
        $pendingLeaveRequests = LeaveRequest::whereIn('user_id', $memberIds)
            ->where('user_id', '!=', $user->id)
            ->where('status', 'pending')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();
        
        return view('leader.dashboard', compact(
            'totalIncomingLeaves',
            'pendingApprovals',
            'quotaSummary',
            'membersOnLeaveThisWeek',
            'pendingLeaveRequests',
            'division'
        ));
    }
}