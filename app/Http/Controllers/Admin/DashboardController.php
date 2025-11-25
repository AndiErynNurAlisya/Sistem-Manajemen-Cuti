<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\Division;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Total karyawan aktif/tidak aktif
        $totalActiveEmployees = User::active()->whereIn('role', ['employee', 'leader'])->count();
        $totalInactiveEmployees = User::where('is_active', false)->whereIn('role', ['employee', 'leader'])->count();
        
        // Total pengajuan cuti bulan ini
        $totalLeavesThisMonth = LeaveRequest::currentMonth()->count();
        
        // Pengajuan pending approval
        $pendingApprovals = LeaveRequest::pending()->count();
        
        // Total divisi
        $totalDivisions = Division::count();
        
        // Karyawan dengan masa kerja < 1 tahun (belum eligible cuti tahunan)
        $newEmployees = User::whereIn('role', ['employee', 'leader'])
            ->where('join_date', '>', now()->subYear())
            ->with('division')
            ->get();
        
        // Recent activities (pengajuan cuti terbaru)
        $recentLeaves = LeaveRequest::with(['user', 'user.division'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalActiveEmployees',
            'totalInactiveEmployees',
            'totalLeavesThisMonth',
            'pendingApprovals',
            'totalDivisions',
            'newEmployees',
            'recentLeaves'
        ));
    }
}