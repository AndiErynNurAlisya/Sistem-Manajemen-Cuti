<x-app-layout>
    <x-slot name="pageTitle">Dashboard</x-slot>
    <x-slot name="pageDescription">Ringkasan data dan aktivitas cuti Anda</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-ui.stat-card-first
            title="Total Pengajuan Masuk"
            :value="$totalIncomingLeaves"
            subtitle="dari anggota divisi"
            color="green"
            icon='<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>'
        />

        <x-ui.stat-card
            title="Menunggu Approval"
            :value="$pendingApprovals"
            subtitle="butuh persetujuan"
            color="white">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card-first
            title="Kuota Cuti Saya"
            :value="$quotaSummary['remaining'] ?? 0"
            :subtitle="'dari ' . ($quotaSummary['total'] ?? 12) . ' hari'"
            color="cream"
            :progress="[
                'current' => $quotaSummary['remaining'] ?? 0,
                'total' => $quotaSummary['total'] ?? 12
            ]"
            icon='<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>'
        />
        
        <x-ui.stat-card
            title="Anggota Cuti Minggu Ini"
            :value="$membersOnLeaveThisWeek->count()"
            subtitle="sedang cuti"
            color="white">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-breeze.quick-action 
                :href="route('leader.approvals.index')"
                title="Review Approvals"
                subtitle=""
                color="gray">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </x-slot:icon>
            </x-breeze.quick-action>

            <x-breeze.quick-action 
                :href="route('leader.leave-requests.create')"
                title="Ajukan Cuti"
                subtitle=""
                color="army">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </x-slot:icon>
            </x-breeze.quick-action>

            <x-breeze.quick-action 
                :href="route('leader.leave-requests.index')"
                title="My Leave Requests"
                subtitle=""
                color="creamarmy">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </x-slot:icon>
            </x-breeze.quick-action>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-ui.approval-list-card
            title="Pending Approvals"
            :leaveRequests="$pendingLeaveRequests"
            :viewAllRoute="route('leader.approvals.index')"
            reviewRouteName="leader.approvals.show"
            :showDivision="false" 
        />

        <x-ui.on-leave-list-card
            title="Sedang Cuti Minggu Ini"
            :employees="$membersOnLeaveThisWeek"
            emptyStateTitle="Tidak ada anggota cuti"
            emptyStateDescription="Semua anggota divisi aktif minggu ini"
            :showDivision="false" 
            :hasMaxHeight="false" 
        />
    </div>
</x-app-layout>