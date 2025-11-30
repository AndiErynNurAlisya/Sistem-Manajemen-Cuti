<x-app-layout>
    <x-slot name="pageTitle">Approval Requests</x-slot>
    <x-slot name="pageDescription">Review pengajuan cuti dari anggota divisi {{ auth()->user()->division->name }}</x-slot>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('leader.approvals.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Karyawan</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Nama karyawan..."
                           class="block w-full shadow-sm sm:text-sm border-[#566534] rounded-md focus:ring-[#566534] focus:border-[#566534]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Cuti</label>
                    <select name="leave_type" 
                            class="block w-full shadow-sm sm:text-sm border-[#566534] rounded-md focus:ring-[#566534] focus:border-[#566534]">
                        <option value="">Semua Jenis</option>
                        <option value="annual" {{ request('leave_type') == 'annual' ? 'selected' : '' }}>Cuti Tahunan</option>
                        <option value="sick" {{ request('leave_type') == 'sick' ? 'selected' : '' }}>Cuti Sakit</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-[#334124] hover:bg-[#566534] text-white text-sm font-medium rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </button>
                @if(request()->hasAny(['search', 'leave_type']))
                    <a href="{{ route('leader.approvals.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($pendingLeaves->isEmpty())
            <x-ui.empty-state
                :title="request()->hasAny(['search', 'leave_type']) ? 'Tidak ditemukan' : 'Tidak ada pengajuan pending'"
                :description="request()->hasAny(['search', 'leave_type']) ? 'Coba ubah filter pencarian' : 'Semua pengajuan sudah diproses'">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </x-slot:icon>
            </x-ui.empty-state>
        @else
            <x-ui.table :headers="['Karyawan', 'Jenis Cuti', 'Periode', 'Durasi', 'Diajukan', 'Aksi']">
                @foreach($pendingLeaves as $leave)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
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
                                        {{ $leave->user->division->name }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-ui.leave-type-badge :type="$leave->leave_type" />
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                    {{ formatDate($leave->start_date) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    s/d {{ formatDate($leave->end_date) }}
                                </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="font-medium">{{ $leave->total_days }}</span> hari
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div>{{ formatDate($leave->request_date) }}</div>
                            <div class="text-xs text-gray-500">{{ $leave->created_at->diffForHumans() }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <x-breeze.button-action
                                :href="route('leader.approvals.show', $leave)"
                                color="army"
                                class="mr-3"
                            >
                                Detail
                            </x-breeze.button-action>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>

            @if($pendingLeaves->hasPages())
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    {{ $pendingLeaves->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>