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

<div class="px-4 py-3" 
     x-data 
     x-show="!$store.sidebar.collapsed"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 -translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="text-xs font-semibold text-gray-500 uppercase mb-2">Leave Quota</div>
    <div class="rounded-lg p-3 bg-gradient-to-b from-[#cfbb99] to-[#e5d7c4]">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm text-gray-900">Annual Leave</span>
            <span class="text-sm font-semibold text-gray-900">{{ $remaining }}/{{ $total }} days</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-[#566534] h-2 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
        </div>
        @if($remaining < 3)
            <p class="text-xs text-red-600 mt-2">⚠️ Low quota remaining</p>
        @endif
    </div>
</div>