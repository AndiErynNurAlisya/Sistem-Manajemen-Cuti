<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="bg-gradient-to-b from-[#334124] to-[#b5b89b] px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-white">Detail Pengajuan</h2>
    </div>
    <div class="p-6 space-y-6">
        <div>
            <dt class="text-sm font-medium text-gray-500 mb-2">Alasan Cuti</dt>
            <dd class="text-sm text-gray-900 bg-gray-50 rounded-lg p-4">{{ $leaveRequest->reason }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-gray-500 mb-2">Alamat Selama Cuti</dt>
            <dd class="text-sm text-gray-900">{{ $leaveRequest->address_during_leave }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-gray-500 mb-2">Nomor Darurat</dt>
            <dd class="text-sm text-gray-900">{{ $leaveRequest->emergency_contact }}</dd>
        </div>

        @if($leaveRequest->leave_type->value === 'sick' && $leaveRequest->medical_certificate)
            <div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Surat Dokter</dt>
                <dd>
                    <a href="{{ $leaveRequest->medical_certificate_url }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                        </svg>
                        Lihat Surat Dokter
                    </a>
                </dd>
            </div>
        @endif
        @if($leaveRequest->leave_type->value === 'annual' && $leaveRequest->medical_certificate)
            <div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Surat Dokter</dt>
                <dd>
                    <a href="{{ $leaveRequest->medical_certificate_url }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                        </svg>
                        Lihat Surat Dokter
                    </a>
                </dd>
            </div>
        @endif

        @if($leaveRequest->status->value === 'rejected' && $leaveRequest->rejection_note)
            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 mb-1">Alasan Penolakan:</p>
                        <p class="text-sm text-red-700">{{ $leaveRequest->rejection_note }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($leaveRequest->status->value === 'cancelled' && $leaveRequest->cancellation_reason)
            <div class="bg-gray-50 border-l-4 border-gray-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-800 mb-1">Alasan Pembatalan:</p>
                        <p class="text-sm text-gray-700">{{ $leaveRequest->cancellation_reason }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>