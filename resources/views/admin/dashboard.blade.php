{{-- resources/views/admin/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <x-breeze.page-header 
    title="Selamat datang, {{ auth()->user()->full_name }}" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

                {{-- Total Karyawan --}}
                <x-ui.stat-card
                    title="Total Karyawan"
                    :value="$totalActiveEmployees + $totalInactiveEmployees"
                    color="indigo"
                    icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>'
                    :href="route('admin.users.index')"
                >
                    <x-slot name="trend">
                        <span class="text-xs text-gray-500 mt-2 block">
                            Aktif: {{ $totalActiveEmployees }}, Nonaktif: {{ $totalInactiveEmployees }}
                        </span>
                    </x-slot>
                </x-ui.stat-card>

                {{-- Total Divisi --}}
                <x-ui.stat-card
                    title="Total Divisi"
                    :value="$totalDivisions"
                    color="blue"
                    icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>'
                    :href="route('admin.divisions.index')"
                />

                {{-- Cuti Bulan Ini --}}
                <x-ui.stat-card
                    title="Cuti Bulan Ini"
                    :value="$totalLeavesThisMonth"
                    color="green"
                    icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>'
                />

                {{-- Pending --}}
                <x-ui.stat-card
                    title="Pending Approval"
                    :value="getPendingApprovalsCount()"
                    color="yellow"
                    icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>'
                />
            </div>

            {{-- Quick Actions --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    {{-- Tambah User --}}
    <x-breeze.quick-action
        href="{{ route('admin.users.create') }}"
        title="Tambah User Baru"
        subtitle="Buat akun karyawan baru"
        color="teal"
        icon='
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M18 9v3m0 0v3m-3-3h3m0 0h3m-6-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        '
    />

    {{-- Tambah Divisi --}}
    <x-breeze.quick-action
        href="{{ route('admin.divisions.create') }}"
        title="Tambah Divisi"
        subtitle="Tambahkan divisi baru perusahaan"
        color="teal"
        icon='
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        '
    />

    {{-- Lihat User --}}
    <x-breeze.quick-action
        href="{{ route('admin.users.index') }}"
        title="Lihat Semua User"
        subtitle="Daftar lengkap seluruh karyawan"
        color="teal"
        icon='
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        '
    />

</div>


            {{-- Karyawan Baru --}}
            @if($newEmployees->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Karyawan Baru (&lt; 1 Tahun)</h3>
                            <x-breeze.link-more href="#" text="Lihat semua " color="black" />
                        </div>

                        <x-ui.table :headers="['Nama','Email','Divisi','Bergabung']">
                            @foreach($newEmployees as $emp)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 font-semibold text-sm">
                                                    {{ getUserInitials($emp) }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $emp->full_name }}</div>
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

            {{-- Recent Leave Requests --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pengajuan Cuti Terbaru</h3>
                        <x-breeze.link-more href="#" text="Lihat semua " color="black" />
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

                                    {{-- Karyawan --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                                <span class="text-gray-600 font-semibold text-sm">
                                                    {{ getUserInitials($leave->user) }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $leave->user->full_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Divisi --}}
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $leave->user->division->name ?? '-' }}
                                    </td>

                                    {{-- Jenis Cuti --}}
                                    <td class="px-6 py-4">
                                        @php $type = formatLeaveType($leave->leave_type); @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $type['class'] }}">
                                            <span class="mr-1">{{ $type['icon'] }}</span>
                                            {{ $type['text'] }}
                                        </span>
                                    </td>
 
                                    {{-- Periode --}}
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ formatDate($leave->start_date, 'd M') }} - {{ formatDate($leave->end_date, 'd M Y') }}
                                    </td>

                                    {{-- Status --}}
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
