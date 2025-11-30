@props([
    'title',
    'employees', // Nama variabel disederhanakan
    'emptyStateTitle',
    'emptyStateDescription',
    'showDivision' => true, // Prop untuk mengontrol tampilan divisi (hanya perlu di HRD)
    'hasMaxHeight' => false, // Prop untuk mengontrol max-h-96 overflow-y-auto (hanya perlu di HRD)
])

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">{{ $title }}</h2>
    </div>

    @if($employees->isEmpty())
        <x-ui.empty-state
            title="{{ $emptyStateTitle }}"
            description="{{ $emptyStateDescription }}">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </x-slot:icon>
        </x-ui.empty-state>
    @else
        <div @class(['divide-y divide-gray-200', 'max-h-96 overflow-y-auto' => $hasMaxHeight])>
            @foreach($employees as $leave)
                <div class="p-6">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200">
                                <img src="{{ $leave->user->profile_photo_url }}" 
                                    alt="{{ $leave->user->full_name }}" 
                                    class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900">{{ $leave->user->full_name }}</p>
                            @if($showDivision)
                                <p class="text-xs text-gray-500 mb-1">{{ $leave->user->division->name ?? '-' }}</p>
                            @endif
                            <div class="flex items-center space-x-2 mt-1">
                                <x-ui.leave-type-badge :type="$leave->leave_type" />
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>