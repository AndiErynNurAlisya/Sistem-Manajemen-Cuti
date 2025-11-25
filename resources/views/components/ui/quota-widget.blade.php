{{-- resources/views/components/ui/quota-widget.blade.php --}}
@props(['user' => null])

@php
    $user = $user ?? auth()->user();
    $quota = getLeaveQuota($user);
    
    // Ensure we get the right structure
    $remaining = is_array($quota) ? ($quota['remaining'] ?? 0) : ($quota->remaining_quota ?? 0);
    $total = is_array($quota) ? ($quota['total'] ?? 12) : ($quota->total_quota ?? 12);
    $used = is_array($quota) ? ($quota['used'] ?? 0) : ($quota->used_quota ?? 0);
    
    $percentage = $total > 0 ? round(($remaining / $total) * 100) : 0;
@endphp

<div class="px-4 py-3">
    <div class="text-xs font-semibold text-gray-500 uppercase mb-2">Leave Quota</div>
    <div class="bg-gray-50 rounded-lg p-3">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm text-gray-600">Annual Leave</span>
            <span class="text-sm font-semibold text-gray-900">{{ $remaining }}/{{ $total }} days</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-indigo-600 h-2 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
        </div>
        @if($remaining < 3)
            <p class="text-xs text-red-600 mt-2">⚠️ Low quota remaining</p>
        @endif
    </div>
</div>