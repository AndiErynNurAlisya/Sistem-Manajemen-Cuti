<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\LeaveRequest;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends BaseController
{
    /**
     * Display comprehensive reports
     */
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['user', 'user.division', 'approvals.approver']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->filled('division_id')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $year = $request->input('year', date('Y'));
        if ($request->filled('year')) {
            $query->whereYear('created_at', $year);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        $query->latest('created_at');
        
        $leaves = $this->paginate($query);
        
        $divisions = Division::all();
        
        // === STATISTICS ===
        
        $stats = [
            'total' => LeaveRequest::whereYear('created_at', $year)->count(),
            'pending' => LeaveRequest::whereYear('created_at', $year)->where('status', 'pending')->count(),
            'approved' => LeaveRequest::whereYear('created_at', $year)->where('status', 'approved')->count(),
            'rejected' => LeaveRequest::whereYear('created_at', $year)->where('status', 'rejected')->count(),
            'cancelled' => LeaveRequest::whereYear('created_at', $year)->where('status', 'cancelled')->count(),
        ];
        
        $divisionStats = LeaveRequest::whereYear('created_at', $year)
            ->with('user.division')
            ->get()
            ->groupBy('user.division.name')
            ->map(function($items) {
                return [
                    'total' => $items->count(),
                    'approved' => $items->where('status', 'approved')->count(),
                    'rejected' => $items->where('status', 'rejected')->count(),
                    'pending' => $items->where('status', 'pending')->count(),
                ];
            });
        
        // Top user cuti
        $topUsers = LeaveRequest::whereYear('created_at', $year)
            ->select('user_id', DB::raw('count(*) as total_leaves'))
            ->groupBy('user_id')
            ->orderByDesc('total_leaves')
            ->limit(10)
            ->with('user.division')
            ->get();
        
        return view('admin.reports.index', compact(
            'leaves',
            'divisions',
            'stats',
            'divisionStats',
            'topUsers',
            'year'
        ));
    }
}