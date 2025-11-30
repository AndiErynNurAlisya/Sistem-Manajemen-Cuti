@props([
    'submitRoute',
    'cancelRoute',
    'quota' => ['total' => 12, 'used' => 0, 'remaining' => 12],
    'canRequestAnnual' => true,
    'monthsOfService' => 12,
    'remainingMonths' => 0,
    'showLeaderInfo' => false,
    'showEligibilityWarning' => true,
])

<div x-data="leaveRequestForm({{ $canRequestAnnual ? 'true' : 'false' }})" x-init="init()">

    <form method="POST" 
          action="{{ $submitRoute }}" 
          enctype="multipart/form-data" 
          class="bg-white rounded-lg shadow-sm"
          @submit="isSubmitting = true">
        @csrf

        <div class="p-6 space-y-6">
            
            {{-- Leave Type Selection --}}
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #334124;">
                    Jenis Cuti <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-4">
                    {{-- Annual Leave --}}
                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition {{ $canRequestAnnual ? '' : 'opacity-50 cursor-not-allowed' }}"
                           :class="formData.leave_type === 'annual' ? 'bg-opacity-10' : 'border-gray-200 hover:border-gray-300'"
                           style="border-color: #566534;">
                        <input type="radio" 
                               name="leave_type" 
                               value="annual"
                               x-model="formData.leave_type"
                               @change="onLeaveTypeChange()"
                               style="color: #566534; focus:ring-color: #566534;"
                               {{ !$canRequestAnnual ? 'disabled' : '' }}
                               {{ old('leave_type', $canRequestAnnual ? 'annual' : 'sick') === 'annual' && $canRequestAnnual ? 'checked' : '' }}>
                        <div class="ml-3">
                            <div class="text-sm font-medium flex items-center" style="color: #334124;">
                                Cuti Tahunan
                                @if(!$canRequestAnnual)
                                    <svg class="w-4 h-4 ml-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="text-xs {{ $canRequestAnnual ? 'text-gray-500' : 'text-red-600' }}">
                                {{ $canRequestAnnual ? 'Minimal H+3' : 'Belum memenuhi syarat' }}
                            </div>
                        </div>
                    </label>

                    {{-- Sick Leave --}}
                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition"
                           :class="formData.leave_type === 'sick' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-gray-300'">
                        <input type="radio" 
                               name="leave_type" 
                               value="sick"
                               x-model="formData.leave_type"
                               @change="onLeaveTypeChange()"
                               class="text-red-600 focus:ring-red-500"
                               {{ old('leave_type', $canRequestAnnual ? '' : 'sick') === 'sick' || !$canRequestAnnual ? 'checked' : '' }}>
                        <div class="ml-3">
                            <div class="text-sm font-medium" style="color: #334124;">Cuti Sakit</div>
                            <div class="text-xs text-gray-500">Wajib surat dokter</div>
                        </div>
                    </label>
                </div>
                @error('leave_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Date Range --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #334124;">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="start_date"
                           x-model="formData.start_date"
                           @change="calculateWorkingDays()"
                           :min="minStartDate"
                           required
                           value="{{ old('start_date') }}"
                           class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:border-opacity-100 @error('start_date') border-red-500 @enderror"
                           style="focus:border-color: #566534; focus:ring-color: #566534;">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <template x-if="formData.leave_type === 'annual'">
                        <p class="mt-1 text-xs text-gray-500">⚠️ Cuti tahunan minimal H+3</p>
                    </template>
                    <template x-if="formData.leave_type === 'sick'">
                        <p class="mt-1 text-xs text-gray-500">ℹ️ Cuti sakit bisa mulai hari ini</p>
                    </template>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #334124;">
                        Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="end_date"
                           x-model="formData.end_date"
                           @change="calculateWorkingDays()"
                           :min="formData.start_date || minStartDate"
                           required
                           value="{{ old('end_date') }}"
                           class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('end_date') border-red-500 @enderror">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Working Days Info --}}
            <div x-show="workingDays > 0" 
                 x-transition
                 class="p-4 rounded-lg border-l-4"
                 style="background-color: rgba(181, 184, 155, 0.15); border-color: #566534;">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5" style="color: #566534;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm" style="color: #334124;">
                            Total hari kerja: <strong x-text="workingDays"></strong> hari (Weekend tidak dihitung)
                        </p>
                    </div>
                </div>
            </div>

            {{-- Reason --}}
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #334124;">
                    Alasan Cuti <span class="text-red-500">*</span>
                </label>
                <textarea name="reason"
                          x-model="formData.reason"
                          rows="4"
                          required
                          maxlength="500"
                          class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('reason') border-red-500 @enderror"
                          placeholder="Jelaskan alasan pengajuan cuti Anda (minimal 10 karakter)">{{ old('reason') }}</textarea>
                <div class="flex justify-between mt-1">
                    @error('reason')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="text-xs text-gray-500">Minimal 10 karakter</p>
                    @enderror
                    <p class="text-xs text-gray-500" x-text="`${formData.reason.length}/500`"></p>
                </div>
            </div>

            {{-- Address & Emergency Contact Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #334124;">
                        Alamat Selama Cuti <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="address_during_leave"
                           x-model="formData.address_during_leave"
                           required
                           maxlength="255"
                           value="{{ old('address_during_leave') }}"
                           class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('address_during_leave') border-red-500 @enderror"
                           placeholder="Alamat yang bisa dihubungi">
                    @error('address_during_leave')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #334124;">
                        Nomor Darurat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="emergency_contact"
                           x-model="formData.emergency_contact"
                           required
                           maxlength="20"
                           value="{{ old('emergency_contact') }}"
                           class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('emergency_contact') border-red-500 @enderror"
                           placeholder="08xxxxxxxxxx">
                    @error('emergency_contact')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Medical Certificate Upload (Sick Leave Only) --}}
            <div x-show="formData.leave_type === 'sick'" x-transition>
                <label class="block text-sm font-medium mb-2" style="color: #334124;">
                    Surat Dokter <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition"
                     @dragover.prevent="isDragging = true"
                     @dragleave.prevent="isDragging = false"
                     @drop.prevent="handleDrop($event)"
                     :class="isDragging ? 'bg-opacity-10' : ''"
                     style="border-color: {{ $canRequestAnnual ? '#d1d5db' : '#566534' }};">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium hover:opacity-80 transition">
                                <span style="color: #566534;">Upload file</span>
                                <input type="file" 
                                       name="medical_certificate"
                                       accept=".pdf,.jpg,.jpeg,.png"
                                       @change="handleFileUpload($event)"
                                       x-ref="fileInput"
                                       class="sr-only">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF, JPG, JPEG, PNG maksimal 2MB</p>
                        <template x-if="fileName">
                            <p class="text-sm text-green-600 font-medium mt-2 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span x-text="fileName"></span>
                            </p>
                        </template>
                    </div>
                </div>
                @error('medical_certificate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="px-6 py-4 flex items-center justify-end space-x-3 rounded-b-lg" style="background-color: #f9fafb;">
            <a href="{{ $cancelRoute }}" 
               class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                    :disabled="isSubmitting"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition disabled:opacity-50 disabled:cursor-not-allowed"
                    style="background-color: #334124;"
                    onmouseover="this.style.backgroundColor='#566534'"
                    onmouseout="this.style.backgroundColor='#334124'">
                <span x-show="!isSubmitting">Ajukan Cuti</span>
                <span x-show="isSubmitting" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function leaveRequestForm(canRequestAnnual = true) {
        return {
            formData: {
                leave_type: '{{ old('leave_type') }}' || (canRequestAnnual ? 'annual' : 'sick'),
                start_date: '{{ old('start_date') }}',
                end_date: '{{ old('end_date') }}',
                reason: '{{ old('reason') }}',
                address_during_leave: '{{ old('address_during_leave') }}',
                emergency_contact: '{{ old('emergency_contact') }}'
            },
            workingDays: 0,
            fileName: '',
            isSubmitting: false,
            isDragging: false,
            minStartDate: '',
            canRequestAnnual: canRequestAnnual,
            
            init() {
                if (!this.canRequestAnnual && this.formData.leave_type === 'annual') {
                    this.formData.leave_type = 'sick';
                }
                this.updateMinStartDate();
                if (this.formData.start_date && this.formData.end_date) {
                    this.calculateWorkingDays();
                }
            },
            
            onLeaveTypeChange() {
                if (this.formData.leave_type === 'annual' && !this.canRequestAnnual) {
                    alert('Anda belum memenuhi syarat untuk mengajukan cuti tahunan. Minimal masa kerja 1 tahun.');
                    this.formData.leave_type = 'sick';
                    return;
                }
                this.updateMinStartDate();
                if (this.formData.start_date && this.formData.start_date < this.minStartDate) {
                    this.formData.start_date = this.minStartDate;
                }
                this.calculateWorkingDays();
            },
            
            updateMinStartDate() {
                const today = new Date();
                if (this.formData.leave_type === 'annual') {
                    today.setDate(today.getDate() + 3);
                }
                this.minStartDate = today.toISOString().split('T')[0];
            },
            
            async calculateWorkingDays() {
                if (!this.formData.start_date || !this.formData.end_date) {
                    this.workingDays = 0;
                    return;
                }
                
                const start = new Date(this.formData.start_date);
                const end = new Date(this.formData.end_date);
                
                if (end < start) {
                    this.workingDays = 0;
                    return;
                }
                
                let count = 0;
                for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
                    const day = d.getDay();
                    if (day !== 0 && day !== 6) count++;
                }
                this.workingDays = count;
            },
            
            handleFileUpload(event) {
                const file = event.target.files[0];
                if (file) this.setFile(file);
            },
            
            handleDrop(event) {
                this.isDragging = false;
                const file = event.dataTransfer.files[0];
                if (file) {
                    const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                    if (validTypes.includes(file.type)) {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        this.$refs.fileInput.files = dataTransfer.files;
                        this.setFile(file);
                    } else {
                        alert('Format file tidak valid. Hanya PDF, JPG, JPEG, PNG yang diizinkan.');
                    }
                }
            },
            
            setFile(file) {
                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    this.fileName = '';
                    this.$refs.fileInput.value = '';
                    return;
                }
                this.fileName = file.name;
            }
        }
    }
</script>
@endpush