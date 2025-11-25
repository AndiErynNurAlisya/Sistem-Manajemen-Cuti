{{-- resources/views/employee/leave-requests/index.blade.php --}}
<x-app-layout>
    <x-slot name="title">My Leave Requests</x-slot>

    {{-- Page Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Leave Requests</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola dan pantau pengajuan cuti Anda</p>
        </div>
        <a href="{{ route('employee.leave-requests.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Ajukan Cuti Baru
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('employee.leave-requests.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" 
                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="approved_by_leader" {{ request('status') == 'approved_by_leader' ? 'selected' : '' }}>Disetujui Ketua Divisi</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
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

                {{-- Start Date Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" 
                           name="start_date" 
                           value="{{ request('start_date') }}"
                           class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                {{-- End Date Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" 
                           name="end_date" 
                           value="{{ request('end_date') }}"
                           class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
                @if(request()->hasAny(['status', 'leave_type', 'start_date', 'end_date']))
                    <a href="{{ route('employee.leave-requests.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Leave Requests Table --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($leaveRequests->isEmpty())
            <x-ui.empty-state
                :title="request()->hasAny(['status', 'leave_type', 'start_date', 'end_date']) 
                    ? 'Tidak ada pengajuan cuti' 
                    : 'Belum ada pengajuan cuti'"
                :description="request()->hasAny(['status', 'leave_type', 'start_date', 'end_date'])
                    ? 'Coba ubah filter pencarian Anda'
                    : 'Belum ada pengajuan cuti yang dibuat'"
                :actionUrl="!request()->hasAny(['status', 'leave_type', 'start_date', 'end_date']) 
                    ? route('employee.leave-requests.create') 
                    : null"
                :actionText="!request()->hasAny(['status', 'leave_type', 'start_date', 'end_date']) 
                    ? 'Ajukan Cuti Sekarang' 
                    : null">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </x-slot:icon>
            </x-ui.empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Cuti</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Ajuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approval</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($leaveRequests as $leave)
                            <tr class="hover:bg-gray-50 transition">
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
                                    {{ $leave->request_date->format('d M Y') }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-ui.leave-status-badge :status="$leave->status" />
                                </td>

                                {{-- Approval Progress --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        @if($leave->approvals->isNotEmpty())
                                            @foreach($leave->approvals as $approval)
                                                @if($approval->status === 'approved')
                                                    <div class="flex items-center" title="{{ $approval->approver->full_name }} ({{ ucfirst($approval->approver_role) }})">
                                                        <span class="text-green-600 text-sm">✓</span>
                                                        <span class="text-xs text-gray-600 ml-1">{{ ucfirst($approval->approver_role) }}</span>
                                                    </div>
                                                @elseif($approval->status === 'rejected')
                                                    <div class="flex items-center" title="{{ $approval->approver->full_name }} ({{ ucfirst($approval->approver_role) }})">
                                                        <span class="text-red-600 text-sm">✗</span>
                                                        <span class="text-xs text-gray-600 ml-1">{{ ucfirst($approval->approver_role) }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="text-xs text-gray-400">Belum ada approval</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('employee.leave-requests.show', $leave) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Detail
                                    </a>
                                    @if($leave->canBeCancelled())
                                        <button type="button"
                                                onclick="openCancelModal({{ $leave->id }})"
                                                class="text-red-600 hover:text-red-900">
                                            Batalkan
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($leaveRequests->hasPages())
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    {{ $leaveRequests->links() }}
                </div>
            @endif
        @endif
    </div>

    {{-- Cancel Modal --}}
    <div x-data="{ 
            showModal: false, 
            leaveId: null,
            cancellationReason: '',
            isSubmitting: false
         }"
         x-show="showModal"
         x-cloak
         @open-cancel-modal.window="showModal = true; leaveId = $event.detail.id"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        {{-- Backdrop --}}
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                 @click="showModal = false">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            {{-- Modal Content --}}
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                
                <form :action="`{{ route('employee.leave-requests.index') }}/${leaveId}/cancel`" 
                      method="POST"
                      @submit="isSubmitting = true">
                    @csrf
                    <div class="bg-white px-6 pt-5 pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Batalkan Pengajuan Cuti
                                </h3>
                                <div class="mt-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Alasan Pembatalan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="cancellation_reason"
                                              x-model="cancellationReason"
                                              rows="4"
                                              required
                                              class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"
                                              placeholder="Jelaskan alasan pembatalan (minimal 10 karakter)"></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Minimal 10 karakter</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 sm:flex sm:flex-row-reverse gap-3">
                        <button type="submit"
                                :disabled="cancellationReason.length < 10 || isSubmitting"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!isSubmitting">Batalkan Cuti</span>
                            <span x-show="isSubmitting">Memproses...</span>
                        </button>
                        <button type="button"
                                @click="showModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openCancelModal(leaveId) {
            window.dispatchEvent(new CustomEvent('open-cancel-modal', { detail: { id: leaveId } }));
        }
    </script>
    @endpush
</x-app-layout>