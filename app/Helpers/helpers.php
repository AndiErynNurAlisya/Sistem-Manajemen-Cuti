<?php

use Carbon\Carbon;


if (!function_exists('getPendingApprovalsCount')) {
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

if (!function_exists('getLeaveQuota')) {

    function getLeaveQuota($user): array
    {
        $quota = $user->leaveQuota; 
        
        if (!$quota) {
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

    function formatLeaveStatus($status)
    {
        $badges = [
            'pending' => ['text' => 'Pending', 'class' => 'bg-[#b5b89b] text-[#334124]'], 
            'approved_by_leader' => ['text' => 'Approved by Leader', 'class' => 'bg-[#566534] text-white'], 
            'approved' => ['text' => 'Approved', 'class' => 'bg-[#334124] text-white'],
            'rejected' => ['text' => 'Rejected', 'class' => 'bg-red-100 text-red-800'],
            'cancelled' => ['text' => 'Cancelled', 'class' => 'bg-gray-100 text-gray-800'],
        ];
        
        $statusValue = is_object($status) ? $status->value : $status;
        
        return $badges[$statusValue] ?? ['text' => ucfirst($statusValue), 'class' => 'bg-gray-100 text-gray-800'];
    }
}

if (!function_exists('formatDate')) {

    function formatDate($date, $format = 'd M Y')
    {
        if (!$date) return '-';
        
        return Carbon::parse($date)->format($format);
    }
}

if (!function_exists('fileExists')) {

    function fileExists(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }
        
        return \Storage::disk('public')->exists($path);
    }
}

if (!function_exists('uploadProfilePhoto')) {

    function uploadProfilePhoto($file, $oldPath = null): string
    {
        if ($oldPath && fileExists($oldPath)) {
            \Storage::disk('public')->delete($oldPath);
        }

        $filename = 'profile_' . uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('profile_photos', $filename, 'public');
        
        return $path;
    }
}

if (!function_exists('getProfilePhotoUrl')) {

    function getProfilePhotoUrl($user = null): string
    {
        $user = $user ?? auth()->user();
        
        if ($user && $user->profile_photo && fileExists($user->profile_photo)) {
            return asset('storage/' . $user->profile_photo);
        }
        
        $name = $user->full_name ?? $user->name ?? 'User';
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=b5b89b&background=334124&size=200&bold=true';
    }
}

if (!function_exists('deleteProfilePhoto')) {

    function deleteProfilePhoto(string $path): bool
    {
        if (fileExists($path)) {
            return \Storage::disk('public')->delete($path);
        }
        
        return false;
    }
}

if (!function_exists('canRequestAnnualLeave')) {

    function canRequestAnnualLeave($user = null): bool
    {
        $user = $user ?? auth()->user();
        
        if (!$user || !$user->join_date) {
            return false;
        }
        
        $monthsOfService = Carbon::parse($user->join_date)->diffInMonths(now());
        
        return $monthsOfService >= 12;
    }
}

if (!function_exists('getMonthsOfService')) {

    function getMonthsOfService($user = null): int
    {
        $user = $user ?? auth()->user();
        
        if (!$user || !$user->join_date) {
            return 0;
        }
        
        return Carbon::parse($user->join_date)->diffInMonths(now());
    }
}

if (!function_exists('getRemainingMonthsToEligible')) {
    function getRemainingMonthsToEligible($user = null): int
    {
        $user = $user ?? auth()->user();
        
        if (canRequestAnnualLeave($user)) {
            return 0;
        }
        
        $monthsOfService = getMonthsOfService($user);
        
        return max(0, 12 - $monthsOfService); 
    }
}