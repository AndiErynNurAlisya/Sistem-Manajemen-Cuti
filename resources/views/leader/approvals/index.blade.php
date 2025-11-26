{{-- resources/views/leader/approvals/index.blade.php --}}
<x-app-layout>
    <x-slot name="title">Approval Requests</x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Approval Requests</h1>
        <p class="text-sm text-gray-600 mt-1">Review pengajuan cuti dari anggota divisi {{ auth()->user()->division->name }}</p>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('leader.approvals.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Search by Name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Karyawan</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Nama karyawan..."
                           class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                {{-- Leave Type Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Cuti</label>
                    <select name="leave_type" 
                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Jenis</option>
                        <option value="annual" {{ request('leave_type') == 'annual' ? 'selected' : '' }}>Cuti Tahunan</option>
                        <option value="sick" {{ request('leave_type') == 'sick' ? 'selected' : '' }}>Cuti Sakit</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
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

    {{-- Pending Requests Table --}}
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
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Cuti</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diajukan</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingLeaves as $leave)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Employee Name --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 font-semibold text-sm">
                                                    {{ substr($leave->user->full_name, 0, 2) }}
                                                </span>
                                            </div>
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

                                {{-- Leave Type --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-ui.leave-type-badge :type="$leave->leave_type" />
                                </td>

                                {{-- Period --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $leave->start_date->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        s/d {{ $leave->end_date->format('d M Y') }}
                                    </div>
                                </td>

                                {{-- Duration --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="font-medium">{{ $leave->total_days }}</span> hari
                                </td>

                                {{-- Request Date --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div>{{ $leave->request_date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $leave->created_at->diffForHumans() }}</div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('leader.approvals.show', $leave) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pendingLeaves->hasPages())
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    {{ $pendingLeaves->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>