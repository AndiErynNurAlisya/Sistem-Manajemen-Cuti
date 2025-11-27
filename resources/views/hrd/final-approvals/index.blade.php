{{-- resources/views/hrd/final-approvals/index.blade.php --}}
<x-app-layout>
    <x-slot name="title">Final Approvals - HRD</x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Final Approvals</h1>
        <p class="text-sm text-gray-600 mt-1">Review dan setujui pengajuan cuti karyawan</p>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('hrd.final-approvals.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                        Cari Karyawan
                    </label>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Nama karyawan..."
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                {{-- Leave Type Filter --}}
                <div>
                    <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-1">
                        Jenis Cuti
                    </label>
                    <select name="leave_type" 
                            id="leave_type"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Jenis</option>
                        <option value="annual" {{ request('leave_type') === 'annual' ? 'selected' : '' }}>Cuti Tahunan</option>
                        <option value="sick" {{ request('leave_type') === 'sick' ? 'selected' : '' }}>Cuti Sakit</option>
                    </select>
                </div>

                {{-- Division Filter --}}
                <div>
                    <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Divisi
                    </label>
                    <select name="division_id" 
                            id="division_id"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Divisi</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                {{ $division->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'leave_type', 'division_id']))
                        <a href="{{ route('hrd.final-approvals.index') }}" 
                           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Batch Actions (if any selected) --}}
    <div x-data="{ selectedIds: [], selectAll: false }" 
         x-init="$watch('selectAll', value => selectedIds = value ? {{ $pendingApprovals->pluck('id') }} : [])"
         class="space-y-6">
        
        {{-- Batch Action Bar --}}
        <div x-show="selectedIds.length > 0" 
             x-transition
             class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-indigo-900">
                        <span x-text="selectedIds.length"></span> pengajuan dipilih
                    </span>
                    <button @click="selectAll = false; selectedIds = []" 
                            class="text-sm text-indigo-600 hover:text-indigo-800">
                        Batal Pilih
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <button @click="if(confirm('Setujui ' + selectedIds.length + ' pengajuan?')) { document.getElementById('batch-approve-form').submit(); }"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Approve All
                    </button>
                </div>
            </div>
            
            {{-- Hidden Form for Batch Approve --}}
            <form id="batch-approve-form" 
                  method="POST" 
                  action="{{ route('hrd.final-approvals.batch') }}"
                  class="hidden">
                @csrf
                <template x-for="id in selectedIds" :key="id">
                    <input type="hidden" name="leave_request_ids[]" :value="id">
                </template>
            </form>
        </div>

        {{-- Results Count --}}
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">
                Menampilkan {{ $pendingApprovals->count() }} dari {{ $pendingApprovals->total() }} pengajuan
            </p>
        </div>

        {{-- Leave Requests List --}}
        @if($pendingApprovals->isEmpty())
            <div class="bg-white rounded-lg shadow-sm">
                <x-ui.empty-state
                    title="Tidak ada pengajuan"
                    description="Tidak ada pengajuan yang perlu di-review saat ini">
                    <x-slot:icon>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </x-slot:icon>
                </x-ui.empty-state>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-12 px-6 py-3">
                                <input type="checkbox" 
                                       x-model="selectAll"
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Karyawan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis Cuti
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Periode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Durasi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingApprovals as $leave)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <input type="checkbox" 
                                           :value="{{ $leave->id }}"
                                           x-model="selectedIds"
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </td>
                                <td class="px-6 py-4">
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
                                                {{ $leave->user->division->name ?? 'No Division' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-ui.leave-type-badge :type="$leave->leave_type" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>{{ $leave->start_date->format('d M Y') }}</div>
                                    <div class="text-gray-500">{{ $leave->end_date->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $leave->total_days }} hari
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($leave->status->value === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Dari Leader
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Approved by Leader
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('hrd.final-approvals.show', $leave) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $pendingApprovals->links() }}
            </div>
        @endif
    </div>
</x-app-layout>