<?php

// app/Helpers/helpers.php

if (!function_exists('isActiveRoute')) {
    /**
     * Check if current route matches pattern
     */
    function isActiveRoute($pattern, $output = 'bg-indigo-50 text-indigo-600')
    {
        $currentRoute = request()->route()->getName();
        return str_starts_with($currentRoute, $pattern) ? $output : '';
    }
}

if (!function_exists('getPendingApprovalsCount')) {
    /**
     * Get pending approvals count based on user role
     */
    function getPendingApprovalsCount()
    {
        $user = auth()->user();
        
        if (!$user) return 0;
        
        switch ($user->role->value) {
            case 'leader':
                return \App\Models\LeaveRequest::whereHas('user', function($q) use ($user) {
                    $q->where('division_id', $user->division_id);
                })
                ->where('status', 'pending')
                ->where('user_id', '!=', $user->id)
                ->count();
                
            case 'hrd':
                return \App\Models\LeaveRequest::whereIn('status', ['pending', 'approved_by_leader'])
                    ->count();
                
            default:
                return 0;
        }
    }
}

if (!function_exists('getUserInitials')) {
    /**
     * Get user initials from name
     */
    function getUserInitials($user = null)
    {
        $user = $user ?? auth()->user();
        $name = $user->full_name ?? $user->name;
        
        return strtoupper(substr($name, 0, 2));
    }
}

// if (!function_exists('getLeaveQuota')) {
//     /**
//      * Get current user's leave quota
//      */
//     function getLeaveQuota($user = null)
//     {
//         $user = $user ?? auth()->user();
//         return $user->leaveQuota;
//     }
// }

if (!function_exists('getLeaveQuota')) {
    /**
     * Get leave quota for user - KONSISTEN RETURN FORMAT
     * 
     * @param \App\Models\User $user
     * @return array Always return array format
     */
    function getLeaveQuota($user): array
    {
        $quota = $user->leaveQuota; // Get LeaveQuota model for current year
        
        if (!$quota) {
            // Jika belum ada quota, return default
            return [
                'total' => 12,
                'used' => 0,
                'remaining' => 12,
            ];
        }
        
        return [
            'total' => $quota->total_quota,
            'used' => $quota->used_quota,
            'remaining' => $quota->remaining_quota,
        ];
    }
}



if (!function_exists('formatLeaveStatus')) {
    /**
     * Format leave status with badge color
     */
    function formatLeaveStatus($status)
    {
        $badges = [
            'pending' => ['text' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-800'],
            'approved_by_leader' => ['text' => 'Approved by Leader', 'class' => 'bg-blue-100 text-blue-800'],
            'approved' => ['text' => 'Approved', 'class' => 'bg-green-100 text-green-800'],
            'rejected' => ['text' => 'Rejected', 'class' => 'bg-red-100 text-red-800'],
            'cancelled' => ['text' => 'Cancelled', 'class' => 'bg-gray-100 text-gray-800'],
        ];
        
        $statusValue = is_object($status) ? $status->value : $status;
        
        return $badges[$statusValue] ?? ['text' => ucfirst($statusValue), 'class' => 'bg-gray-100 text-gray-800'];
    }
}

if (!function_exists('formatLeaveType')) {
    /**
     * Format leave type with icon and color
     */
    function formatLeaveType($type)
    {
        $types = [
            'annual' => [
                'text' => 'Annual Leave',
                'icon' => '🏖️',
                'class' => 'bg-blue-100 text-blue-800'
            ],
            'sick' => [
                'text' => 'Sick Leave',
                'icon' => '🏥',
                'class' => 'bg-red-100 text-red-800'
            ],
        ];
        
        $typeValue = is_object($type) ? $type->value : $type;
        
        return $types[$typeValue] ?? ['text' => ucfirst($typeValue), 'icon' => '📄', 'class' => 'bg-gray-100 text-gray-800'];
    }
}

if (!function_exists('getApprovalRoute')) {
    /**
     * Get approval route based on user role
     */
    function getApprovalRoute()
    {
        $role = auth()->user()->role->value;
        
        return match($role) {
            'leader' => route('leader.approvals.index'),
            'hrd' => route('hrd.final-approvals.index'),
            default => '#'
        };
    }
}

if (!function_exists('canApproveLeave')) {
    /**
     * Check if user can approve leaves
     */
    function canApproveLeave()
    {
        return in_array(auth()->user()->role->value, ['leader', 'hrd']);
    }
}

if (!function_exists('getDashboardRoute')) {
    /**
     * Get dashboard route based on user role
     */
    function getDashboardRoute($role = null)
    {
        $role = $role ?? auth()->user()->role->value;
        
        return route("{$role}.dashboard");
    }
}

if (!function_exists('formatDate')) {
    /**
     * Format date to Indonesian format
     */
    function formatDate($date, $format = 'd M Y')
    {
        if (!$date) return '-';
        
        return \Carbon\Carbon::parse($date)->format($format);
    }
}

if (!function_exists('calculateQuotaPercentage')) {
    /**
     * Calculate quota percentage for progress bar
     */
    function calculateQuotaPercentage($remaining, $total = 12)
    {
        if ($total == 0) return 0;
        return round(($remaining / $total) * 100);
    }
}

if (!function_exists('fileExists')) {
    /**
     * Check if file exists in storage
     * 
     * @param string|null $path
     * @return bool
     */
    function fileExists(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }
        
        return \Storage::disk('public')->exists($path);
    }
}