{{-- resources/views/hrd/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="title">Dashboard - HRD</x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard HRD</h1>
        <p class="text-sm text-gray-600 mt-1">Selamat datang, {{ auth()->user()->full_name }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Total Leaves This Month --}}
        <x-ui.stat-card
            title="Total Cuti Bulan Ini"
            :value="$totalLeavesThisMonth"
            subtitle="pengajuan cuti"
            color="blue">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </x-slot:icon>
        </x-ui.stat-card>

        {{-- Pending Final Approvals --}}
        <x-ui.stat-card
            title="Menunggu Approval"
            :value="$pendingFinalApprovals"
            subtitle="butuh final approval"
            color="yellow">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </x-slot:icon>
        </x-ui.stat-card>

        {{-- Annual Leaves This Month --}}
        <x-ui.stat-card
            title="Cuti Tahunan"
            :value="$annualLeavesThisMonth"
            subtitle="bulan ini"
            color="indigo">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </x-slot:icon>
        </x-ui.stat-card>

        {{-- Sick Leaves This Month --}}
        <x-ui.stat-card
            title="Cuti Sakit"
            :value="$sickLeavesThisMonth"
            subtitle="bulan ini"
            color="red">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-breeze.quick-action 
                :href="route('hrd.final-approvals.index')"
                title="Review Final Approvals"
                subtitle="Proses persetujuan akhir"
                color="yellow">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </x-slot:icon>
            </x-breeze.quick-action>

            <x-breeze.quick-action 
                :href="route('hrd.final-approvals.index', ['status' => 'approved'])"
                title="History Cuti"
                subtitle="Lihat riwayat semua cuti"
                color="indigo">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </x-slot:icon>
            </x-breeze.quick-action>

            <x-breeze.quick-action 
                :href="route('hrd.dashboard') . '#divisions'"
                title="Data Divisi"
                subtitle="Lihat data per divisi"
                color="gray">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </x-slot:icon>
            </x-breeze.quick-action>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pending Final Approvals List --}}
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Pending Final Approvals</h2>
                    <a href="{{ route('hrd.final-approvals.index') }}" 
                       class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        Lihat Semua →
                    </a>
                </div>
            </div>

            @if($recentPendingApprovals->isEmpty())
                <x-ui.empty-state
                    title="Tidak ada pengajuan pending"
                    description="Semua pengajuan sudah diproses">
                    <x-slot:icon>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </x-slot:icon>
                </x-ui.empty-state>
            @else
                <div class="divide-y divide-gray-200">
                    @foreach($recentPendingApprovals as $leave)
                        <div class="p-6 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <p class="font-semibold text-gray-900">{{ $leave->user->full_name }}</p>
                                        <x-ui.leave-type-badge :type="$leave->leave_type" />
                                    </div>
                                    <p class="text-xs text-gray-500 mb-1">
                                        {{ $leave->user->division->name ?? 'No Division' }}
                                    </p>
                                    <p class="text-sm text-gray-600 mb-1">
                                        {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                                        ({{ $leave->total_days }} hari)
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Diajukan {{ $leave->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <a href="{{ route('hrd.final-approvals.show', $leave) }}" 
                                   class="ml-4 inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                                    Review
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Employees on Leave This Month --}}
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Karyawan Cuti Bulan Ini</h2>
            </div>

            @if($employeesOnLeaveThisMonth->isEmpty())
                <x-ui.empty-state
                    title="Tidak ada karyawan cuti"
                    description="Semua karyawan aktif bulan ini">
                    <x-slot:icon>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </x-slot:icon>
                </x-ui.empty-state>
            @else
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                    @foreach($employeesOnLeaveThisMonth as $leave)
                        <div class="p-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-indigo-600 font-semibold text-sm">
                                            {{ substr($leave->user->full_name, 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900">{{ $leave->user->full_name }}</p>
                                    <p class="text-xs text-gray-500 mb-1">{{ $leave->user->division->name ?? '-' }}</p>
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
    </div>

    {{-- Division Stats --}}
    <div class="bg-white rounded-lg shadow-sm mt-6" id="divisions">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Data Divisi</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($divisions as $division)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $division->name }}</h3>
                                @if($division->leader)
                                    <p class="text-xs text-gray-500 mt-1">
                                        Ketua: {{ $division->leader->full_name }}
                                    </p>
                                @else
                                    <p class="text-xs text-gray-400 mt-1">Belum ada ketua</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Total Anggota:</span>
                            <span class="font-semibold text-gray-900">{{ $division->members_count }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm mt-2">
                            <span class="text-gray-600">Aktif:</span>
                            <span class="font-semibold text-green-600">{{ $division->active_members_count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>