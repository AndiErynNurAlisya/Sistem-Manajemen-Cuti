{{-- resources/views/leader/leave-requests/create.blade.php --}}
{{-- SAMA dengan employee/leave-requests/create.blade.php, hanya route & info berbeda --}}
<x-app-layout>
    <x-slot name="title">Ajukan Cuti Baru</x-slot>

    <div x-data="leaveRequestForm()" x-init="init()">
        {{-- Breadcrumb --}}
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('leader.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                <span>/</span>
                <a href="{{ route('leader.leave-requests.index') }}" class="hover:text-gray-700">My Leave Requests</a>
                <span>/</span>
                <span class="text-gray-900">Ajukan Cuti Baru</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Ajukan Cuti Baru</h1>
            <p class="text-sm text-gray-600 mt-1">Isi formulir di bawah untuk mengajukan cuti</p>
        </div>

        {{-- Info: Direct to HRD --}}
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Info:</strong> Pengajuan cuti Anda akan langsung direview oleh HRD (tanpa perlu approval dari Ketua Divisi).
                    </p>
                </div>
            </div>
        </div>

        {{-- FORM SAMA PERSIS DENGAN EMPLOYEE, cukup ganti route --}}
        {{-- Copy dari employee-leave-create artifact, ganti route('employee.leave-requests.*') jadi route('leader.leave-requests.*') --}}
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <form method="POST" 
                      action="{{ route('leader.leave-requests.store') }}" 
                      enctype="multipart/form-data" 
                      class="bg-white rounded-lg shadow-sm"
                      @submit="isSubmitting = true">
                    @csrf

                    <div class="p-6 space-y-6">
                        {{-- Leave Type --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Cuti <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition"
                                       :class="formData.leave_type === 'annual' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" 
                                           name="leave_type" 
                                           value="annual"
                                           x-model="formData.leave_type"
                                           @change="onLeaveTypeChange()"
                                           class="text-indigo-600 focus:ring-indigo-500"
                                           {{ old('leave_type', 'annual') === 'annual' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Cuti Tahunan</div>
                                        <div class="text-xs text-gray-500">Minimal H+3</div>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition"
                                       :class="formData.leave_type === 'sick' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" 
                                           name="leave_type" 
                                           value="sick"
                                           x-model="formData.leave_type"
                                           @change="onLeaveTypeChange()"
                                           class="text-red-600 focus:ring-red-500"
                                           {{ old('leave_type') === 'sick' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Cuti Sakit</div>
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
                            {{-- Start Date --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="start_date"
                                       x-model="formData.start_date"
                                       @change="calculateWorkingDays()"
                                       :min="minStartDate"
                                       required
                                       value="{{ old('start_date') }}"
                                       class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('start_date') border-red-500 @enderror">
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

                            {{-- End Date --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="end_date"
                                       x-model="formData.end_date"
                                       @change="calculateWorkingDays()"
                                       :min="formData.start_date || minStartDate"
                                       required
                                       value="{{ old('end_date') }}"
                                       class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Working Days Info --}}
                        <div x-show="workingDays > 0" 
                             x-transition
                             class="bg-blue-50 border-l-4 border-blue-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Total hari kerja: <strong x-text="workingDays"></strong> hari (Weekend tidak dihitung)
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Reason --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Alasan Cuti <span class="text-red-500">*</span>
                            </label>
                            <textarea name="reason"
                                      x-model="formData.reason"
                                      rows="4"
                                      required
                                      maxlength="500"
                                      class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('reason') border-red-500 @enderror"
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

                        {{-- Address During Leave --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Selama Cuti <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="address_during_leave"
                                   x-model="formData.address_during_leave"
                                   required
                                   maxlength="255"
                                   value="{{ old('address_during_leave') }}"
                                   class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('address_during_leave') border-red-500 @enderror"
                                   placeholder="Alamat yang bisa dihubungi selama cuti">
                            @error('address_during_leave')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Emergency Contact --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Darurat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="emergency_contact"
                                   x-model="formData.emergency_contact"
                                   required
                                   maxlength="20"
                                   value="{{ old('emergency_contact') }}"
                                   class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('emergency_contact') border-red-500 @enderror"
                                   placeholder="08xxxxxxxxxx">
                            @error('emergency_contact')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Medical Certificate (Only for sick leave) --}}
                        <div x-show="formData.leave_type === 'sick'" x-transition>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Surat Dokter <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition"
                                 @dragover.prevent="isDragging = true"
                                 @dragleave.prevent="isDragging = false"
                                 @drop.prevent="handleDrop($event)"
                                 :class="isDragging ? 'border-indigo-500 bg-indigo-50' : ''">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload file</span>
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
                    
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 rounded-b-lg">
                        <a href="{{ route('leader.leave-requests.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                                :disabled="isSubmitting"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50">
                            <span x-show="!isSubmitting">Ajukan Cuti</span>
                            <span x-show="isSubmitting">Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Sidebar SAMA DENGAN EMPLOYEE --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Kuota Cuti Anda</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-sm text-gray-600">Total Kuota</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $quota['total'] ?? 12 }} hari</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-sm text-gray-600">Terpakai</span>
                            <span class="text-sm font-semibold text-red-600">{{ $quota['used'] ?? 0 }} hari</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Sisa</span>
                            <span class="text-lg font-bold text-green-600">{{ $quota['remaining'] ?? 12 }} hari</span>
                        </div>
                        
                        {{-- Progress Bar --}}
                        <div class="pt-4">
                            @php
                                $remaining = $quota['remaining'] ?? 12;
                                $total = $quota['total'] ?? 12;
                                $percentage = $total > 0 ? round(($remaining / $total) * 100) : 0;
                            @endphp
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-indigo-600 h-3 rounded-full transition-all" 
                                     style="width: {{ $percentage }}%">
                                </div>
                            </div>
                        </div>

                        @if($remaining < 3)
                            <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-3">
                                <p class="text-xs text-red-700">⚠️ Kuota cuti Anda hampir habis!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- SAME JAVASCRIPT AS EMPLOYEE --}}
    <script>
        // Copy from employee create view
        function leaveRequestForm() {
            return {
                formData: {
                    leave_type: '{{ old('leave_type', 'annual') }}',
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
                
                init() {
                    // Set min date based on leave type
                    this.updateMinStartDate();
                    
                    // Calculate if dates already filled (from validation error)
                    if (this.formData.start_date && this.formData.end_date) {
                        this.calculateWorkingDays();
                    }
                },
                
                onLeaveTypeChange() {
                    this.updateMinStartDate();
                    // Reset start_date if it's less than minStartDate
                    if (this.formData.start_date && this.formData.start_date < this.minStartDate) {
                        this.formData.start_date = this.minStartDate;
                    }
                    this.calculateWorkingDays();
                },
                
                updateMinStartDate() {
                    const today = new Date();
                    
                    // SICK LEAVE = H-0 (bisa hari ini)
                    // ANNUAL LEAVE = H+3
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
                        // Skip weekend: Sunday (0) and Saturday (6)
                        if (day !== 0 && day !== 6) {
                            count++;
                        }
                    }
                    
                    this.workingDays = count;
                },
                
                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.setFile(file);
                    }
                },
                
                handleDrop(event) {
                    this.isDragging = false;
                    const file = event.dataTransfer.files[0];
                    if (file) {
                        // Validate file type
                        const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                        if (validTypes.includes(file.type)) {
                            // Set file to input
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
                    const maxSize = 2 * 1024 * 1024; // 2MB
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
</x-app-layout>

{{-- NOTE: Untuk efisiensi, bisa extract form fields & sidebar ke partials --}}
{{-- resources/views/employee/leave-requests/partials/form-fields.blade.php --}}
{{-- resources/views/employee/leave-requests/partials/sidebar-info.blade.php --}}
{{-- Lalu include di employee & leader create views --}}