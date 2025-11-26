{{-- resources/views/employee/dashboard.blade.php --}}
<x-app-layout>

    {{-- Page Header --}}
    <x-breeze.page-header 
        title="Selamat datang, {{ auth()->user()->full_name }}"/>
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Annual Leave Quota --}}
        <x-ui.stat-card
            title="Sisa Cuti Tahunan"
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

        {{-- Sick Leave Count --}}
        <x-ui.stat-card
            title="Cuti Sakit"
            :value="$totalSickLeaves"
            subtitle="tahun ini"
            color="red">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </x-slot:icon>
        </x-ui.stat-card>

        {{-- Total Requests --}}
        <x-ui.stat-card
            title="Total Pengajuan"
            :value="$totalLeaveRequests"
            subtitle="semua waktu"
            color="blue">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </x-slot:icon>
        </x-ui.stat-card>

        {{-- Pending Requests --}}
        <x-ui.stat-card
            title="Menunggu Approval"
            :value="$pendingLeaves"
            subtitle="pengajuan"
            color="yellow">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-breeze.quick-action 
                :href="route('employee.leave-requests.create')"
                title="Ajukan Cuti"
                subtitle="Buat pengajuan baru"
                color="indigo">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </x-slot:icon>
            </x-breeze.quick-action>

            <x-breeze.quick-action 
                :href="route('employee.leave-requests.index')"
                title="Riwayat Cuti"
                subtitle="Lihat semua pengajuan"
                color="gray">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </x-slot:icon>
            </x-breeze.quick-action>

            <x-breeze.quick-action 
                :href="route('employee.leave-requests.index', ['status' => 'pending'])"
                title="Pending"
                subtitle="Lihat yang menunggu"
                color="yellow">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </x-slot:icon>
            </x-breeze.quick-action>
        </div>
    </div>

    {{-- Recent Leave Requests --}}
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Pengajuan Cuti Terakhir</h2>
                <x-breeze.link-more href="{{ route('employee.leave-requests.index') }}" color="black" />
            </div>
        </div>

        @if($recentLeaves->isEmpty())
            <x-ui.empty-state
                title="Belum ada pengajuan cuti"
                description="Mulai ajukan cuti Anda sekarang"
                :actionUrl="route('employee.leave-requests.create')"
                actionText="Ajukan Cuti">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </x-slot:icon>
            </x-ui.empty-state>
        @else
            <x-ui.table :headers="['Jenis','Periode','Durasi Cuti','Status','Aksi']">

                @foreach($recentLeaves as $leave)
                    <tr class="hover:bg-gray-50 transition">

                        {{-- Jenis --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-ui.leave-type-badge :type="$leave->leave_type" />
                        </td>

                        {{-- Periode --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                        </td>

                        {{-- Durasi --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $leave->total_days }} hari
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-ui.leave-status-badge :status="$leave->status" />
                        </td>
                        <!--buatkan komponen-->

                        {{-- Aksi --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('employee.leave-requests.show', $leave) }}"
                            class="text-indigo-600 hover:text-indigo-900">
                                Detail
                            </a>
                        </td>

                    </tr>
                @endforeach

            </x-ui.table>

                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>