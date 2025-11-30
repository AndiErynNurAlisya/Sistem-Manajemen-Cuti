<x-app-layout>
    <x-slot name="pageTitle">Dashboard</x-slot>
    <x-slot name="pageDescription">Ringkasan data dan aktivitas cuti karyawan</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-ui.stat-card-first
            title="Total Cuti Bulan Ini"
            :value="$totalLeavesThisMonth"
            subtitle="pengajuan cuti"
            color="blue"
            icon='<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>'
        />

        <x-ui.stat-card
            title="Menunggu Approval"
            :value="$pendingFinalApprovals"
            subtitle="butuh final approval"
            color="white">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card-first
            title="Cuti Tahunan"
            :value="$annualLeavesThisMonth"
            subtitle="bulan ini"
            color="cream"
            icon='<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>'
        />


        <x-ui.stat-card
            title="Cuti Sakit"
            :value="$sickLeavesThisMonth"
            subtitle="bulan ini"
            color="white">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-breeze.quick-action 
                :href="route('hrd.final-approvals.index')"
                title="Review Final Approvals"
                subtitle=""
                color="creamarmy">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </x-slot:icon>
            </x-breeze.quick-action>

            <x-breeze.quick-action 
                :href="route('hrd.final-approvals.index', ['status' => 'approved'])"
                title="History Cuti"
                subtitle=""
                color="gray">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </x-slot:icon>
            </x-breeze.quick-action>

            <x-breeze.quick-action 
                :href="route('hrd.dashboard') . '#divisions'"
                title="Data Divisi"
                subtitle=""
                color="army">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </x-slot:icon>
            </x-breeze.quick-action>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-ui.approval-list-card
            title="Pending Approvals"
            :leaveRequests="$recentPendingApprovals"
            :viewAllRoute="route('hrd.final-approvals.index')"
            reviewRouteName="hrd.final-approvals.show"
            :showDivision="false" 
        />

        <x-ui.on-leave-list-card
            title="Karyawan Cuti Bulan Ini"
            :employees="$employeesOnLeaveThisMonth"
            emptyStateTitle="Tidak ada karyawan cuti"
            emptyStateDescription="Semua karyawan aktif bulan ini"
            :showDivision="true" 
            :hasMaxHeight="true" 
        />
    </div>

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