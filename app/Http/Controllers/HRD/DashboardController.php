<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\BaseController;
use App\Models\LeaveRequest;
use App\Models\Division;
use App\Models\User;

class DashboardController extends BaseController
{
    /**
     * Display HRD dashboard
     */
    public function index()
    {
        // Total pengajuan cuti bulan ini
        $totalLeavesThisMonth = LeaveRequest::currentMonth()->count();
        
        // Pengajuan pending final approval
        $pendingFinalApprovals = LeaveRequest::whereIn('status', ['pending', 'approved_by_leader'])
            ->count();
        
        // Karyawan yang sedang cuti bulan ini
        $employeesOnLeaveThisMonth = LeaveRequest::where('status', 'approved')
            ->where('start_date', '<=', now()->endOfMonth())
            ->where('end_date', '>=', now()->startOfMonth())
            ->with('user')
            ->get();
        
        // Daftar divisi
        $divisions = Division::withCount(['members', 'activeMembers'])
            ->with('leader')
            ->get();
        
        // Statistik cuti bulan ini by type
        $annualLeavesThisMonth = LeaveRequest::currentMonth()
            ->where('leave_type', 'annual')
            ->where('status', 'approved')
            ->count();
        
        $sickLeavesThisMonth = LeaveRequest::currentMonth()
            ->where('leave_type', 'sick')
            ->where('status', 'approved')
            ->count();
        
        // Recent final approvals needed
        $recentPendingApprovals = LeaveRequest::whereIn('status', ['pending', 'approved_by_leader'])
            ->with(['user', 'user.division'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('hrd.dashboard', compact(
            'totalLeavesThisMonth',
            'pendingFinalApprovals',
            'employeesOnLeaveThisMonth',
            'divisions',
            'annualLeavesThisMonth',
            'sickLeavesThisMonth',
            'recentPendingApprovals'
        ));
    }
}