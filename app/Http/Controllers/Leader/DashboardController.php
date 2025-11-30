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
        
        $memberIds = $division->members->pluck('id')->toArray();
        
        $totalIncomingLeaves = LeaveRequest::whereIn('user_id', $memberIds)
            ->where('user_id', '!=', $user->id) // Exclude cuti leader sendiri
            ->count();
        
        $pendingApprovals = LeaveRequest::whereIn('user_id', $memberIds)
            ->where('user_id', '!=', $user->id)
            ->where('status', 'pending')
            ->count();
        
        $quotaSummary = $this->quotaService->getSummary($user);
        
        $membersOnLeaveThisWeek = LeaveRequest::whereIn('user_id', $memberIds)
            ->where('status', 'approved')
            ->where('start_date', '<=', now()->endOfWeek())
            ->where('end_date', '>=', now()->startOfWeek())
            ->with('user')
            ->get();
        
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