<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\Division;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    public function index()
    {
        $totalActiveEmployees = User::active()->whereIn('role', ['employee', 'leader'])->count();
        $totalInactiveEmployees = User::where('is_active', false)->whereIn('role', ['employee', 'leader'])->count();
        
        $totalLeavesThisMonth = LeaveRequest::currentMonth()->count();
        
        $pendingApprovals = LeaveRequest::pending()->count();
        
        $totalDivisions = Division::count();

        $newEmployees = User::whereIn('role', ['employee', 'leader'])
            ->where('join_date', '>', now()->subYear())
            ->with('division')
            ->get();
        
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