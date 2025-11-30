<x-app-layout>
    <x-slot name="pageTitle">My Leave Requests</x-slot>
    <x-slot name="pageDescription">Kelola dan pantau pengajuan cuti Anda</x-slot>

    <x-ui.filter-card 
    :action="route('leader.leave-requests.index')"
    type="leave-request"
    /> 

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($leaveRequests->isEmpty())
            <x-ui.empty-state
                title="Belum ada pengajuan cuti"
                description="Mulai ajukan cuti Anda sekarang"
                :actionUrl="route('leader.leave-requests.create')"
                actionText="Ajukan Cuti">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </x-slot:icon>
            </x-ui.empty-state>
        @else
            <x-ui.leave-requests-table 
                :leaveRequests="$leaveRequests" 
                showRoute="leader.leave-requests.show"
                cancelRoute="leader.leave-requests.index"
                :showCancelButton="false"
            />

            @if($leaveRequests->hasPages())
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    {{ $leaveRequests->links() }}
                </div>
            @endif
        @endif
    </div>

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
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal"
                 x-transition
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                 @click="showModal = false">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="showModal"
                 x-transition
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                
                <form :action="`{{ route('leader.leave-requests.index') }}/${leaveId}/cancel`" 
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