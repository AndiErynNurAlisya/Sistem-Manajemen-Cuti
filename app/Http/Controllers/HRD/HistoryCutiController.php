<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\BaseController;
use App\Models\LeaveRequest;
use App\Models\Division;
use Illuminate\Http\Request;

class HistoryCutiController extends BaseController
{
    /**
     * Display history of all leave requests
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

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
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
        
        // Statistics
        $stats = [
            'total' => LeaveRequest::count(),
            'pending' => LeaveRequest::where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('status', 'rejected')->count(),
        ];
        
        return view('hrd.history-cuti.index', compact('leaves', 'divisions', 'stats'));
    }
    
    /**
     * Show detailed leave request
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load([
            'user',
            'user.division',
            'user.leaveQuota',
            'approvals.approver'
        ]);
        
        return view('hrd.history-cuti.show', compact('leaveRequest'));
    }
}