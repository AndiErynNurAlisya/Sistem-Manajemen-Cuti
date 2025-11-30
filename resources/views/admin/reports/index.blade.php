<x-app-layout>
    <x-slot name="pageTitle">Reports & Analytics</x-slot>
    <x-slot name="pageDescription">Laporan lengkap dan analisis pengajuan cuti</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-600">Rejected</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-600">Cancelled</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $stats['cancelled'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Breakdown per Divisi</h3>
            <div class="space-y-4">
                @foreach($divisionStats as $divisionName => $stats)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $divisionName }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $stats['total'] }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[#566534] h-2 rounded-full" 
                                style="width: {{ ($stats['total'] / max(array_sum(array_column($divisionStats->toArray(), 'total')), 1)) * 100 }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>✓ {{ $stats['approved'] }} | ✗ {{ $stats['rejected'] }} | ⏱ {{ $stats['pending'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 10 Pengguna Cuti</h3>
            <div class="space-y-3">
                @forelse($topUsers as $index => $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-[#566534] to-[#b5b89b] flex items-center justify-center">
                                <span class="text-xs font-bold text-white">#{{ $index + 1 }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $item->user->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $item->user->division->name ?? 'No Division' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#b5b89b] text-[#334124]">
                            {{ $item->total_leaves }} cuti
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-sm text-gray-500">Belum ada data cuti untuk tahun {{ $year }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Data</h3>
        <form method="GET" action="{{ route('admin.reports.index') }}" class="space-y-4">
            <input type="hidden" name="year" value="{{ $year }}">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                        Cari Karyawan
                    </label>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Nama karyawan..."
                           class="w-full rounded-lg border-[#566534] focus:border-[#566534] focus:ring-[#566534]">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                        Status
                    </label>
                    <select name="status" id="status"
                            class="w-full rounded-lg border-[#566534] focus:border-[#566534] focus:ring-[#566534]">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-1">
                        Jenis Cuti
                    </label>
                    <select name="leave_type" id="leave_type"
                            class="w-full rounded-lg border-[#566534] focus:border-[#566534] focus:ring-[#566534]">
                        <option value="">Semua</option>
                        <option value="annual" {{ request('leave_type') === 'annual' ? 'selected' : '' }}>Tahunan</option>
                        <option value="sick" {{ request('leave_type') === 'sick' ? 'selected' : '' }}>Sakit</option>
                    </select>
                </div>

                <div>
                    <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Divisi
                    </label>
                    <select name="division_id" id="division_id"
                            class="w-full rounded-lg border-[#566534] focus:border-[#566534] focus:ring-[#566534]">
                        <option value="">Semua</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                {{ $division->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                        Role
                    </label>
                    <select name="role" id="role"
                            class="w-full rounded-lg border-[#566534] focus:border-[#566534] focus:ring-[#566534]">
                        <option value="">Semua</option>
                        <option value="employee" {{ request('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="leader" {{ request('role') === 'leader' ? 'selected' : '' }}>Leader</option>
                        <option value="hrd" {{ request('role') === 'hrd' ? 'selected' : '' }}>HRD</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-[#566534] hover:bg-[#b5b89b] text-white text-sm font-medium rounded-lg transition">
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if($leaves->isEmpty())
        <div class="bg-white rounded-lg shadow-sm">
            <x-ui.empty-state
                title="Tidak ada data"
                description="Belum ada data yang sesuai dengan filter">
            </x-ui.empty-state>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Divisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($leaves as $leave)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="{{ getProfilePhotoUrl($leave->user) }}" 
                                            alt="{{ $leave->user->full_name }}"
                                            class="h-10 w-10 rounded-full object-cover ring-2 ring-white shadow">

                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $leave->user->full_name }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst($leave->user->role->value) }}</div>
                                    </div>
                                    
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $leave->user->division->name ?? '-' }}</td>
                            <td class="px-6 py-4"><x-ui.leave-type-badge :type="$leave->leave_type" /></td>
                            <td class="px-6 py-4 text-sm">
                                {{ formatDate($leave->start_date) }}<br>
                                <span class="text-gray-500">s/d {{ formatDate($leave->end_date) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php $status = formatLeaveStatus($leave->status); @endphp
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $status['class'] }}">
                                    {{ $status['text'] }}
                                </span>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $leaves->links() }}
        </div>
    @endif
</x-app-layout>