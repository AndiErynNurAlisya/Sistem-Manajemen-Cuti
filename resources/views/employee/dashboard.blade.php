<x-app-layout>
    <x-slot name="pageTitle">Dashboard</x-slot>
    <x-slot name="pageDescription">Ringkasan data dan aktivitas cuti Anda</x-slot>


    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <x-ui.stat-card-first
            title="Sisa Cuti Tahunan"
            :value="$quotaSummary['remaining'] ?? 0"
            :subtitle="'dari ' . ($quotaSummary['total'] ?? 12) . ' hari'"
            :current="$quotaSummary['remaining'] ?? 0"
            :total="$quotaSummary['total'] ?? 12"
        />

        <x-ui.stat-card
            title="Cuti Sakit"
            :value="$totalSickLeaves"
            subtitle="tahun ini"
            color="white">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card-first
            title="Total Pengajuan"
            :value="$totalLeaveRequests"
            subtitle="semua waktu"
            color="cream"
            icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'
        />

        <x-ui.stat-card
            title="Menunggu Persetujuan"
            :value="$pendingLeaves"
            subtitle="pengajuan"
            color="white">
            <x-slot:icon>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    <div class="bg-white rounded-lg  p-6 mb-6 shadow-lg">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-breeze.quick-action 
                :href="route('employee.leave-requests.create')"
                title="Ajukan Cuti"
                subtitle=""
                color="creamarmy">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </x-slot:icon>
            </x-breeze.quick-action>

            <x-breeze.quick-action 
                :href="route('employee.leave-requests.index')"
                title="Riwayat Cuti"
                subtitle=""
                color="gray">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </x-slot:icon>
            </x-breeze.quick-action>

            <x-breeze.quick-action 
                :href="route('employee.leave-requests.index', ['status' => 'pending'])"
                title="Pending"
                subtitle=""
                color="army">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </x-slot:icon>
            </x-breeze.quick-action>
        </div>
    </div>

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
                            <div class="text-sm text-gray-900">
                            {{ formatDate($leave->start_date) }}
                        </div>
                        <div class="text-sm text-gray-500">
                            s/d {{ formatDate($leave->end_date) }}
                        </div>
                        </td>

                        {{-- Durasi --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $leave->total_days }} hari
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-ui.leave-status-badge :status="$leave->status" />
                        </td>


                        {{-- Aksi --}}
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <x-breeze.button-action 
                                                href="{{ route('employee.leave-requests.show', $leave) }}" 
                                                color="army">
                                                Detail
                            </x-breeze.button-action>
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