{{-- resources/views/components/navigation/menu-hrd.blade.php --}}

@php
    $pendingCount = getPendingApprovalsCount();
@endphp

<!-- Dashboard -->
<a href="{{ route('hrd.dashboard') }}" 
   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition {{ isActiveRoute('hrd.dashboard', 'bg-indigo-50 text-indigo-600') }} hover:bg-gray-50">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    Dashboard
</a>

<!-- Final Approvals -->
<a href="{{ route('hrd.final-approvals.index') }}" 
   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition {{ isActiveRoute('hrd.final-approvals', 'bg-indigo-50 text-indigo-600') }} hover:bg-gray-50">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Final Approvals
    </div>
    @if($pendingCount > 0)
        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
            {{ $pendingCount }}
        </span>
    @endif
</a>

<!-- Divider -->
<div class="border-t border-gray-200 my-2"></div>

<!-- Section Label -->
<div class="px-3 py-2">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
        Reports & Data
    </p>
</div>

<!-- All Leave Requests (History) -->
<a href="{{ route('hrd.final-approvals.index', ['status' => 'approved']) }}" 
   class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg transition hover:bg-gray-50">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    History Cuti
</a>




