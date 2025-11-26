{{-- resources/views/leader/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="title">Dashboard - Leader</x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-600 mt-1">Selamat datang, {{ auth()->user()->full_name }} - Ketua Divisi {{ $division->name }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Total Incoming Leaves --}}
        <x-ui.stat-card
            title="Total Pengajuan Masuk"
            :value="$totalIncomingLeaves"
            subtitle="dari anggota divisi"
            color="blue">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </x-slot:icon>
        </x-ui.stat-card>

        {{-- Pending Approvals --}}
        <x-ui.stat-card
            title="Menunggu Approval"
            :value="$pendingApprovals"
            subtitle="butuh persetujuan"
            color="yellow">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </x-slot:icon>
        </x-ui.stat-card>

        {{-- Leader's Own Quota --}}
        <x-ui.stat-card
            title="Kuota Cuti Saya"
            :value="$quotaSummary['remaining'] ?? 0"
            :subtitle="'dari ' . ($quotaSummary['total'] ?? 12) . ' hari'"
            color="indigo"
            :progress="[
                'current' => $quotaSummary['remaining'] ?? 0,
                'total' => $quotaSummary['total'] ?? 12
            ]">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </x-slot:icon>
        </x-ui.stat-card>

        {{-- Members on Leave --}}
        <x-ui.stat-card
            title="Anggota Cuti Minggu Ini"
            :value="$membersOnLeaveThisWeek->count()"
            subtitle="sedang cuti"
            color="green">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-breeze.quick-action 
                :href="route('leader.approvals.index')"
                title="Review Approvals"
                subtitle="Proses pengajuan cuti"
                color="yellow">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </x-slot:icon>
            </x-breeze.quick-action>

            <x-breeze.quick-action 
                :href="route('leader.leave-requests.create')"
                title="Ajukan Cuti"
                subtitle="Buat pengajuan baru"
                color="indigo">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </x-slot:icon>
            </x-breeze.quick-action>

            <x-breeze.quick-action 
                :href="route('leader.leave-requests.index')"
                title="My Leave Requests"
                subtitle="Lihat cuti saya"
                color="gray">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </x-slot:icon>
            </x-breeze.quick-action>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pending Approvals List --}}
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Pending Approvals</h2>
                    <a href="{{ route('leader.approvals.index') }}" 
                       class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        Lihat Semua →
                    </a>
                </div>
            </div>

            @if($pendingLeaveRequests->isEmpty())
                <x-ui.empty-state
                    title="Tidak ada pengajuan pending"
                    description="Semua pengajuan sudah diproses">
                    <x-slot:icon>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </x-slot:icon>
                </x-ui.empty-state>
            @else
                <div class="divide-y divide-gray-200">
                    @foreach($pendingLeaveRequests as $leave)
                        <div class="p-6 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <p class="font-semibold text-gray-900">{{ $leave->user->full_name }}</p>
                                        <x-ui.leave-type-badge :type="$leave->leave_type" />
                                    </div>
                                    <p class="text-sm text-gray-600 mb-1">
                                        {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                                        ({{ $leave->total_days }} hari)
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Diajukan {{ $leave->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <a href="{{ route('leader.approvals.show', $leave) }}" 
                                   class="ml-4 inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                                    Review
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Members on Leave This Week --}}
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Anggota Cuti Minggu Ini</h2>
            </div>

            @if($membersOnLeaveThisWeek->isEmpty())
                <x-ui.empty-state
                    title="Tidak ada anggota cuti"
                    description="Semua anggota divisi aktif minggu ini">
                    <x-slot:icon>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </x-slot:icon>
                </x-ui.empty-state>
            @else
                <div class="divide-y divide-gray-200">
                    @foreach($membersOnLeaveThisWeek as $leave)
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
</x-app-layout>