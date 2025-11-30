@php
    $hasMedicalCert = $leaveRequest->leave_type->value === 'sick' && $leaveRequest->medical_certificate;
    $hasRequestLetter = $leaveRequest->leave_type->value === 'annual' && $leaveRequest->request_letter_pdf;
    $hasDocuments = $hasMedicalCert || $hasRequestLetter;
@endphp

@if($hasDocuments)
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b" style="background-color: #f9fafb; border-color: #b5b89b;">
            <h3 class="text-lg font-semibold flex items-center" style="color: #334124;">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                </svg>
                Dokumen Pendukung
            </h3>
        </div>

        <div class="p-6 space-y-4">
            
            @if($hasMedicalCert)
                <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-red-900">Surat Keterangan Dokter</h4>
                            <p class="text-xs text-red-700 mt-1">Dokumen pendukung untuk cuti sakit</p>
                        </div>
                    </div>
                    <a href="{{ $leaveRequest->medical_certificate_url }}" 
                        target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4 " fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        
                    </a>
                </div>
            @endif

            @if($hasRequestLetter)
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-900">Surat Permohonan Cuti</h4>
                            <p class="text-xs text-blue-700 mt-1">Formulir permohonan cuti tahunan</p>
                        </div>
                    </div>
                    <a href="{{ $leaveRequest->request_letter_url }}" 
                        target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        
                    </a>
                </div>
            @endif

        </div>
    </div>
@endif