@props([
    'title',
    'leaveRequests',
    'viewAllRoute',
    'reviewRouteName',
    'emptyStateDescription' => 'Semua pengajuan sudah diproses',
    'showDivision' => false // Prop untuk mengontrol tampilan divisi (hanya perlu di HRD)
])

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">{{ $title }}</h2>
            <x-breeze.link-more 
                :href="$viewAllRoute"  
                
            />
        </div>
    </div>

    @if($leaveRequests->isEmpty())
        <x-ui.empty-state
            title="Tidak ada pengajuan pending"
            description="{{ $emptyStateDescription }}">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </x-slot:icon>
        </x-ui.empty-state>
    @else
        <div class="divide-y divide-gray-200">
            @foreach($leaveRequests as $leave)
                <div class="p-6 hover:bg-gray-50 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <p class="font-semibold text-gray-900">{{ $leave->user->full_name }}</p>
                                <x-ui.leave-type-badge :type="$leave->leave_type" />
                            </div>
                            
                            @if($showDivision)
                                <p class="text-xs text-gray-500 mb-1">
                                    {{ $leave->user->division->name ?? 'No Division' }}
                                </p>
                            @endif
                            
                            <p class="text-sm text-gray-600 mb-1">
                                {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                                ({{ $leave->total_days }} hari)
                            </p>
                            <p class="text-xs text-gray-500">
                                Diajukan {{ $leave->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <a href="{{ route($reviewRouteName, $leave) }}" 
                           class="ml-4 inline-flex items-center px-3 py-1.5 bg-[#334124] hover:bg-[#566534] text-white text-sm font-medium rounded-lg transition">
                            Review
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>