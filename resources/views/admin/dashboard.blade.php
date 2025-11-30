<x-app-layout>
    <x-slot name="pageTitle">Dashboard</x-slot>
    <x-slot name="pageDescription">Ringkasan data dan aktivitas cuti karyawan</x-slot>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <x-ui.stat-card-first
                    title="Total Karyawan"
                    :value="$totalActiveEmployees + $totalInactiveEmployees"
                    subtitle="Aktif: {{ $totalActiveEmployees }} Nonaktif: {{ $totalInactiveEmployees }}"
                    color="army"
                    icon='<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>'
                />
                <x-ui.stat-card
                    title="Total Divisi"
                    :value="$totalDivisions"
                    subtitle="terdaftar"
                    color="white"
                    icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>'
                    :href="route('admin.divisions.index')"
                />
                <x-ui.stat-card-first
                    title="Cuti Bulan Ini"
                    :value="$totalLeavesThisMonth"
                    subtitle="total pengajuan"
                    color="cream"
                    icon='<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>'
                />
                <x-ui.stat-card
                    title="Pending Approval"
                    :value="getPendingApprovalsCount()"
                    subtitle="butuh approval"
                    color="white"
                    icon='<svg class="w-6 h-6 mt-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>'
                />
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-breeze.quick-action
                        href="{{ route('admin.users.create') }}"
                        title="Tambah User Baru"
                        subtitle=""
                        color="creamarmy"
                        icon='
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m-3-3h3m0 0h3m-6-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        '
                    />
                    <x-breeze.quick-action
                        href="{{ route('admin.divisions.create') }}"
                        title="Tambah Divisi"
                        subtitle=""
                        color="gray"
                        icon='
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        '
                    />
                    <x-breeze.quick-action
                        href="{{ route('admin.users.index') }}"
                        title="Lihat Semua User"
                        subtitle=""
                        color="army"
                        icon='
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        '
                    />
                </div>
            </div>


            @if($newEmployees->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Karyawan Baru (< 1 Tahun)</h3>
                            <x-breeze.link-more href="{{ route('admin.users.index') }}" text="Lihat semua " color="black" />
                        </div>

                        <x-ui.table :headers="['Nama','Email','Divisi','Bergabung']">
                            @foreach($newEmployees as $emp)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img 
                                                    src="{{ getProfilePhotoUrl($emp) }}" 
                                                    alt="{{ $emp->full_name }}"
                                                    class="h-10 w-10 rounded-full object-cover ring-2 ring-white shadow"
                                                >
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $emp->full_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $emp->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $emp->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-ui.division-badge :name="$emp->division->name ?? '-'" />
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ formatDate($emp->join_date) }}
                                    </td>
                                </tr>
                            @endforeach
                        </x-ui.table>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pengajuan Cuti Terbaru</h3>
                        <x-breeze.link-more href="{{ route('admin.reports.index') }}" text="Lihat semua " color="black" />
                    </div>

                    @if($recentLeaves->isEmpty())
                        <x-ui.empty-state 
                            title="Belum ada pengajuan cuti"
                            description="Pengajuan cuti dari karyawan akan muncul di sini"
                        />
                    @else
                        <x-ui.table :headers="['Karyawan','Divisi','Jenis','Periode','Status']">
                            @foreach($recentLeaves as $leave)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img 
                                                    src="{{ getProfilePhotoUrl($leave->user) }}" 
                                                    alt="{{ $leave->user->full_name }}"
                                                    class="h-10 w-10 rounded-full object-cover ring-2 ring-white shadow"
                                                >
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $leave->user->full_name }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $leave->user->division->name ?? 'No Division' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $leave->user->division->name ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4"><x-ui.leave-type-badge :type="$leave->leave_type" /></td>
                               
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ formatDate($leave->start_date, 'd M') }} - {{ formatDate($leave->end_date, 'd M Y') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @php $status = formatLeaveStatus($leave->status); @endphp
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $status['class'] }}">
                                            {{ $status['text'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </x-ui.table>
                    @endif
                </div>
            </div>

        </div>
    </div>

</x-app-layout>