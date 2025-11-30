<x-app-layout>
    <x-slot name="pageTitle">Final Approval </x-slot>
    <x-slot name="pageDescription">Review dan setujui pengajuan cuti </x-slot>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('hrd.final-approvals.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-1">
                        Jenis Cuti
                    </label>
                    <select name="leave_type" 
                            id="leave_type"
                            class="w-full rounded-lg border-[#566534] focus:border-[#566534] focus:ring-[#566534]">
                        <option value="">Semua Jenis</option>
                        <option value="annual" {{ request('leave_type') === 'annual' ? 'selected' : '' }}>Cuti Tahunan</option>
                        <option value="sick" {{ request('leave_type') === 'sick' ? 'selected' : '' }}>Cuti Sakit</option>
                    </select>
                </div>

                <div>
                    <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Divisi
                    </label>
                    <select name="division_id" 
                            id="division_id"
                            class="w-full rounded-lg border-[#566534] focus:border-[#566534] focus:ring-[#566534]">
                        <option value="">Semua Divisi</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                {{ $division->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-[#566534] hover:bg-[#b5b89b] text-white text-sm font-medium rounded-lg transition">
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

    <div x-data="{ selectedIds: [], selectAll: false }" 
         x-init="$watch('selectAll', value => selectedIds = value ? {{ $pendingApprovals->pluck('id') }} : [])"
         class="space-y-6">
        
        <div x-show="selectedIds.length > 0" 
             x-transition
             class="bg-[#b5b89b] border border-[#334124] rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-white">
                        <span x-text="selectedIds.length"></span> pengajuan dipilih
                    </span>
                    <button @click="selectAll = false; selectedIds = []" 
                            class="text-sm text-red-600 hover:text-red-700">
                        Batal 
                    </button>
                </div>
                <div class="flex items-center space-x-2">

                    <button @click="if(confirm('Setujui ' + selectedIds.length + ' pengajuan?')) { document.getElementById('batch-approve-form').submit(); }"
                            class="inline-flex items-center px-4 py-2 bg-[#334124] hover:bg-[#566534] text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Approve All
                    </button>

                    <button @click="$dispatch('open-reject-modal')"
                            x-show="selectedIds.length > 0"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reject All
                    </button>

                    <div x-data="{ 
                                open: false, 
                                reason: '' 
                            }"
                        @open-reject-modal.window="open = true"
                        x-show="open"
                        x-cloak
                        class="fixed inset-0 z-50 overflow-y-auto"
                        style="display: none;">
                        
                        <div class="flex items-center justify-center min-h-screen px-4">
                            <div @click="open = false" 
                                class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
                            
                            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    Tolak <span x-text="selectedIds.length"></span> Pengajuan
                                </h3>
                                
                                <form id="batch-reject-form-with-reason" 
                                    method="POST" 
                                    action="{{ route('hrd.final-approvals.batch-reject') }}">
                                    @csrf
                                    
                                    <template x-for="id in selectedIds" :key="id">
                                        <input type="hidden" name="leave_request_ids[]" :value="id">
                                    </template>
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Alasan Penolakan <span class="text-red-500">*</span>
                                        </label>
                                        <textarea 
                                            x-model="reason"
                                            name="notes"
                                            required
                                            rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                            placeholder="Masukkan alasan penolakan..."></textarea>
                                    </div>
                                    
                                    <div class="flex justify-end space-x-3">
                                        <button type="button"
                                                @click="open = false; reason = ''"
                                                class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                            Batal
                                        </button>
                                        <button type="submit"
                                                :disabled="!reason.trim()"
                                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                                            Tolak Semua
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="batch-approve-form" method="POST" 
            action="{{ route('hrd.final-approvals.batch') }}"
            class="hidden">
            @csrf
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" name="leave_request_ids[]" :value="id">
            </template>
        </form>
    </div>


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
                                   class="rounded border-gray-300 text-[#b5b89b] focus:ring-[#b5b89b]">
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
                                       class="rounded border-gray-300 text-[#b5b89b] focus:ring-[#b5b89b]">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="flex-shrink-0">
                                            <img 
                                                src="{{ getProfilePhotoUrl($leave->user) }}" 
                                                alt="{{ $leave->user->full_name }}"
                                                class="h-10 w-10 rounded-full object-cover ring-2 ring-white shadow"
                                            >
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
                                <div class="text-sm text-gray-900">
                                    {{ formatDate($leave->start_date) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    s/d {{ formatDate($leave->end_date) }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $leave->total_days }} hari
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($leave->status->value === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#334124] text-white">
                                        Dari Leader
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#b5b89b] text-[#334124]">
                                        Approved by Leader
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('hrd.final-approvals.show', $leave) }}" 
                                class="inline-flex items-center px-3 py-1.5 bg-[#334124] hover:bg-[#2a361e] text-white rounded-lg transition">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                                        class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">
                Menampilkan {{ $pendingApprovals->count() }} dari {{ $pendingApprovals->total() }} pengajuan
            </p>
        </div>

        <div class="mt-6">
            {{ $pendingApprovals->links() }}
        </div>
    @endif
</x-app-layout>