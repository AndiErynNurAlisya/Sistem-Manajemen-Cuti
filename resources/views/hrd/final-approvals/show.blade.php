{{-- resources/views/hrd/final-approvals/show.blade.php --}}
<x-app-layout>
    <x-slot name="title">Review Pengajuan Cuti - HRD</x-slot>

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('hrd.final-approvals.index') }}" 
           class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Employee Info --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Karyawan</h2>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-bold text-xl">
                                {{ substr($leaveRequest->user->full_name, 0, 2) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $leaveRequest->user->full_name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $leaveRequest->user->email }}</p>
                        <div class="flex items-center space-x-4 mt-3">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ $leaveRequest->user->division->name ?? 'No Division' }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $leaveRequest->user->phone ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quota Info --}}
                @if($leaveRequest->leave_type->value === 'annual')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Kuota Cuti Tahunan</h4>
                                <p class="text-xs text-gray-500 mt-1">Tahun {{ now()->year }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ $leaveRequest->user->leaveQuota->remaining_quota ?? 0 }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    dari {{ $leaveRequest->user->leaveQuota->total_quota ?? 12 }} hari
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Leave Request Details --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Pengajuan Cuti</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Cuti</label>
                        <x-ui.leave-type-badge :type="$leaveRequest->leave_type" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        @php $statusInfo = formatLeaveStatus($leaveRequest->status); @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusInfo['class'] }}">
                            {{ $statusInfo['text'] }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <p class="text-gray-900">{{ $leaveRequest->start_date->format('d F Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <p class="text-gray-900">{{ $leaveRequest->end_date->format('d F Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Hari</label>
                        <p class="text-gray-900 font-semibold">{{ $leaveRequest->total_days }} hari kerja</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan</label>
                        <p class="text-gray-900">{{ $leaveRequest->created_at->format('d F Y H:i') }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Cuti</label>
                    <p class="text-gray-900 bg-gray-50 rounded-lg p-4">{{ $leaveRequest->reason }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Selama Cuti</label>
                        <p class="text-gray-900">{{ $leaveRequest->address_during_leave }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kontak Darurat</label>
                        <p class="text-gray-900">{{ $leaveRequest->emergency_contact }}</p>
                    </div>
                </div>
            </div>

            {{-- Documents --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Dokumen</h2>
                
                <div class="space-y-3">
                    {{-- Medical Certificate (Cuti Sakit) --}}
                    @if($leaveRequest->leave_type->value === 'sick' && $leaveRequest->medical_certificate)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Surat Dokter</p>
                                    <p class="text-xs text-gray-500">Surat keterangan sakit</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $leaveRequest->medical_certificate) }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat
                            </a>
                        </div>
                    @endif

                    {{-- Request Letter (Cuti Tahunan) --}}
                    @if($leaveRequest->leave_type->value === 'annual' && $leaveRequest->request_letter_pdf)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Surat Permohonan Cuti</p>
                                    <p class="text-xs text-gray-500">Surat pengajuan dari karyawan</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $leaveRequest->request_letter_pdf) }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Approval Timeline --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline Approval</h2>
                
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($timeline as $index => $item)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    
                                    <div class="relative flex space-x-3">
                                        <div>
                                            @if($item['type'] === 'submitted')
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            @elseif($item['type'] === 'approved')
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            @elseif($item['type'] === 'rejected')
                                                <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $item['label'] }}</p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $item['user']->full_name ?? 'System' }} • 
                                                    {{ $item['date']->format('d M Y H:i') }}
                                                </p>
                                            </div>
                                            @if($item['notes'])
                                                <div class="mt-2 text-sm text-gray-700 bg-gray-50 rounded p-3">
                                                    <p class="font-medium text-xs text-gray-500 mb-1">Catatan:</p>
                                                    {{ $item['notes'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Sidebar Actions --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Final Approval</h3>
                
                {{-- Approval Form --}}
                <div x-data="{ 
                    action: '', 
                    notes: '',
                    showRejectReason: false 
                }">
                    
                    {{-- Notes Input --}}
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan <span x-show="action === 'reject'" class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="notes" 
                            x-model="notes"
                            rows="4"
                            :required="action === 'reject'"
                            placeholder="Tambahkan catatan (opsional untuk approve, wajib untuk reject)"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Minimal 10 karakter untuk penolakan
                        </p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="space-y-3">
                        {{-- Approve Button --}}
                        <form method="POST" 
                              action="{{ route('hrd.final-approvals.approve', $leaveRequest) }}"
                              @submit="if (action !== 'approve') { event.preventDefault(); return false; }">
                            @csrf
                            <input type="hidden" name="notes" :value="notes">
                            
                            <button type="submit"
                                    @click="action = 'approve'"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Approve Cuti
                            </button>
                        </form>

                        {{-- Reject Button --}}
                        <form method="POST" 
                              action="{{ route('hrd.final-approvals.reject', $leaveRequest) }}"
                              @submit.prevent="
                                  if (action === 'reject' && notes.length >= 10) { 
                                      if(confirm('Yakin ingin menolak pengajuan ini?')) {
                                          $el.submit();
                                      }
                                  } else {
                                      alert('Alasan penolakan minimal 10 karakter');
                                  }
                              ">
                            @csrf
                            <input type="hidden" name="notes" :value="notes">
                            
                            <button type="submit"
                                    @click="action = 'reject'"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reject Cuti
                            </button>
                        </form>
                    </div>

                    {{-- Info Box --}}
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Perhatian:</strong> Setelah di-approve, surat izin cuti akan otomatis di-generate dan dikirim ke karyawan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>