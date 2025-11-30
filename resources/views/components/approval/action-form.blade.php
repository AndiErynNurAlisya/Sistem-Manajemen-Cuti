<div class="bg-white rounded-lg shadow-sm overflow-hidden" x-data="{ 
    action: '', 
    notes: '',
    isSubmitting: false,
    validateReject() {
        if (this.action === 'reject' && this.notes.length < 10) {
            alert('Alasan penolakan minimal 10 karakter');
            return false;
        }
        return true;
    },
    submitForm() {
        if (!this.validateReject()) return;
        
        const form = document.getElementById('approvalForm');
        form.action = (this.action === 'approve') ? '{{ $approveRoute }}' : '{{ $rejectRoute }}';
        this.isSubmitting = true;
        form.submit();
    }
}">
    <div class="px-6 py-4 border-b" style="background-color: #f9fafb; border-color: #b5b89b;">
        <h3 class="text-lg font-semibold" style="color: #334124;">Keputusan Approval</h3>
    </div>

    <div class="p-6">
        <form id="approvalForm" method="POST" action="">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2" style="color: #334124;">
                    Catatan
                    <span x-show="action === 'reject'" class="text-red-600">*</span>
                    <span x-show="action === 'approve'" class="text-gray-500 font-normal">(Opsional)</span>
                </label>
                <textarea 
                    name="notes"
                    x-model="notes"
                    rows="4"
                    :disabled="action === ''"
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-2 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed transition"
                    style="focus:ring-color: #566534;"
                    :placeholder="action === 'reject' ? 'Jelaskan alasan penolakan (minimal 10 karakter) *' : 'Catatan tambahan untuk karyawan (opsional)'"></textarea>

                <p x-show="action === 'reject'" class="mt-1 text-xs text-red-600">
                    <span x-text="notes.length"></span>/10 karakter minimum
                </p>

                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col md:flex-row md:justify-end space-y-3 md:space-y-0 md:space-x-3">
                <button type="button"
                    @click="action = 'reject'; notes = ''"
                    :class="action === 'reject' ? 'ring-2 ring-red-500 ring-offset-2' : ''"
                    class="flex-1 md:flex-none px-4 py-2.5 border-2 border-red-500 rounded-lg text-sm font-semibold text-red-700 bg-red-50 hover:bg-red-100 transition-all">
                    Tolak
                </button>

                <button type="button"
                    @click="action = 'approve'; notes = ''"
                    :class="action === 'approve' ? 'ring-2 ring-green-500 ring-offset-2' : ''"
                    class="flex-1 md:flex-none px-4 py-2.5 border-2 border-green-500 rounded-lg text-sm font-semibold text-green-700 bg-green-50 hover:bg-green-100 transition-all">
                    Setujui
                </button>
            </div>

            <div x-show="action !== ''" 
                x-transition
                class="mt-6 p-5 rounded-lg border-2 w-full" 
                :class="action === 'approve' ? 'bg-green-50 border-green-300' : 'bg-red-50 border-red-300'">

                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg x-show="action === 'approve'" class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>

                            <svg x-show="action === 'reject'" class="h-6 w-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <div class="flex-1">
                            <p class="text-sm font-semibold" :class="action === 'approve' ? 'text-green-900' : 'text-red-900'">
                                <span x-show="action === 'approve'">Anda akan menyetujui pengajuan cuti ini</span>
                                <span x-show="action === 'reject'">Anda akan menolak pengajuan cuti ini</span>
                            </p>
                            <p class="text-xs mt-1" :class="action === 'approve' ? 'text-green-700' : 'text-red-700'">
                                <span x-show="action === 'approve'">Pengajuan akan diteruskan ke HRD untuk approval final.</span>
                                <span x-show="action === 'reject'">Pastikan alasan penolakan sudah jelas dan sopan.</span>
                            </p>
                        </div>
                    </div>

                    <button type="button"
                        @click="submitForm()"
                        :disabled="isSubmitting || (action === 'reject' && notes.length < 10)"
                        class="ml-4 inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-bold text-white shadow-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="action === 'approve' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'">
                        <span x-show="!isSubmitting" class="flex items-center">
                            <svg class="w-4 h-4 " fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            KIRIM
                        </span>
                        <span x-show="isSubmitting" class="flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div> 
            </div> 

        </form>
    </div>
</div>