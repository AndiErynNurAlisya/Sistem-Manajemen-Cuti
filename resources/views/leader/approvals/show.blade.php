{{-- resources/views/leader/approvals/show.blade.php --}}
<x-app-layout>
    <x-slot name="title">Review Pengajuan Cuti</x-slot>

    <div x-data="{ 
        action: '', 
        notes: '',
        isSubmitting: false
    }">
        {{-- Breadcrumb --}}
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('leader.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                <span>/</span>
                <a href="{{ route('leader.approvals.index') }}" class="hover:text-gray-700">Approval Requests</a>
                <span>/</span>
                <span class="text-gray-900">Review</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Review Pengajuan Cuti</h1>
            <p class="text-sm text-gray-600 mt-1">Pengajuan dari {{ $leaveRequest->user->full_name }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Employee Information --}}
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white">Informasi Karyawan</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-600 font-bold text-xl">
                                        {{ substr($leaveRequest->user->full_name, 0, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <dl class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Nama Lengkap</dt>
                                        <dd class="text-sm font-semibold text-gray-900">{{ $leaveRequest->user->full_name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Divisi</dt>
                                        <dd class="text-sm text-gray-900">{{ $leaveRequest->user->division->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Email</dt>
                                        <dd class="text-sm text-gray-900">{{ $leaveRequest->user->email }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Kuota Tersisa</dt>
                                        <dd class="text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $leaveRequest->user->leaveQuota->remaining_quota ?? 0 }}/{{ $leaveRequest->user->leaveQuota->total_quota ?? 12 }} hari
                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Leave Request Details --}}
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Detail Pengajuan Cuti</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">Jenis Cuti</dt>
                                <dd><x-ui.leave-type-badge :type="$leaveRequest->leave_type" /></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">Tanggal Pengajuan</dt>
                                <dd class="text-sm text-gray-900">{{ $leaveRequest->request_date->format('d F Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">Tanggal Mulai</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ $leaveRequest->start_date->format('d F Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">Tanggal Selesai</dt>
                                <dd class="text-sm font-semibold text-gray-900">{{ $leaveRequest->end_date->format('d F Y') }}</dd>
                            </div>
                            
                            {{-- PERBAIKAN: Durasi dan Alasan Cuti sejajar --}}
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">Durasi</dt>
                                <dd>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        {{ $leaveRequest->total_days }} hari kerja
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">Alasan Cuti</dt>
                                <dd class="text-sm text-gray-900 bg-gray-50 rounded-lg p-3">{{ $leaveRequest->reason }}</dd>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">Alamat Selama Cuti</dt>
                                <dd class="text-sm text-gray-900">{{ $leaveRequest->address_during_leave }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">Nomor Darurat</dt>
                                <dd class="text-sm text-gray-900">{{ $leaveRequest->emergency_contact }}</dd>
                            </div>
                        </div>

                        {{-- Surat Dokter (Cuti Sakit) --}}
                        @if($leaveRequest->leave_type->value === 'sick' && $leaveRequest->medical_certificate)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">Surat Dokter</dt>
                                <dd>
                                    <a href="{{ $leaveRequest->medical_certificate_url }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                        </svg>
                                        Lihat Surat Dokter
                                    </a>
                                </dd>
                            </div>
                        @endif

                        {{-- Surat Permohonan Cuti (Cuti Tahunan) --}}
                        @if($leaveRequest->leave_type->value === 'annual' && $leaveRequest->request_letter_pdf)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">Surat Permohonan Cuti</dt>
                                <dd>
                                    <a href="{{ $leaveRequest->request_letter_url }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                        </svg>
                                        Lihat Surat Permohonan Cuti
                                    </a>
                                </dd>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Approval Form --}}
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Keputusan Approval</h2>
                    </div>

                    <div class="p-6">
                        <form id="approvalForm" method="POST" action="">
                            @csrf
                            
                            {{-- Notes Textarea --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan 
                                    <span x-show="action === 'reject'" class="text-red-500">*</span>
                                    <span x-show="action === 'approve'" class="text-gray-500">(Optional)</span>
                                </label>
                                <textarea 
                                    name="notes"
                                    x-model="notes"
                                    rows="4"
                                    :required="action === 'reject'"
                                    :disabled="action === ''"
                                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                    :placeholder="action === 'reject' ? 'Jelaskan alasan penolakan (minimal 10 karakter)' : 'Catatan tambahan (optional)'"></textarea>

                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('leader.approvals.index') }}" 
                                   class="px-6 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Kembali
                                </a>

                                <button type="button"
                                        @click="action = 'reject'"
                                        class="px-6 py-2.5 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                    Tolak
                                </button>

                                <button type="button"
                                        @click="action = 'approve'"
                                        class="px-6 py-2.5 border border-green-300 rounded-lg text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                                    Setujui
                                </button>
                            </div>

                            {{-- Submit Confirmation --}}
                            <div x-show="action !== ''" 
                                 x-transition
                                 class="mt-6 p-4 rounded-lg"
                                 :class="action === 'approve' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">

                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg x-show="action === 'approve'" class="h-5 w-5 text-green-400" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                        </svg>

                                        <svg x-show="action === 'reject'" class="h-5 w-5 text-red-400" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                        </svg>
                                    </div>

                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium" :class="action === 'approve' ? 'text-green-800' : 'text-red-800'">
                                            <span x-show="action === 'approve'">Setujui pengajuan cuti ini?</span>
                                            <span x-show="action === 'reject'">Tolak pengajuan cuti ini?</span>
                                        </p>
                                    </div>

                                    <button type="button"
                                        @click="
                                            const form = document.getElementById('approvalForm');
                                            form.action = (action === 'approve') 
                                                ? '{{ route('leader.approvals.approve', $leaveRequest) }}'
                                                : '{{ route('leader.approvals.reject', $leaveRequest) }}';
                                            isSubmitting = true;
                                            form.submit();
                                        "
                                        :disabled="isSubmitting"
                                        class="ml-4 inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        :class="action === 'approve' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'">
                                        <span x-show="!isSubmitting">Konfirmasi</span>
                                        <span x-show="isSubmitting">Memproses...</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Sidebar Info --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Status Info --}}
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Informasi
                    </h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        <p>• Pengajuan ini menunggu persetujuan Anda sebagai Ketua Divisi.</p>
                        <p>• Setelah disetujui, pengajuan akan diteruskan ke HRD untuk approval final.</p>
                        <p>• Jika ditolak, kuota karyawan akan dikembalikan (untuk cuti tahunan).</p>
                    </div>
                </div>

                {{-- Important Notes --}}
                <div class="bg-yellow-50 rounded-lg shadow-sm p-6 border-l-4 border-yellow-400">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Catatan Penting</h3>
                    <ul class="space-y-2 text-xs text-gray-700">
                        <li class="flex items-start">
                            <span class="text-yellow-600 mr-2">•</span>
                            <span>Pastikan periode cuti tidak bentrok dengan jadwal penting</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-yellow-600 mr-2">•</span>
                            <span>Cek kuota tersisa karyawan sebelum menyetujui</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-yellow-600 mr-2">•</span>
                            <span>Untuk cuti sakit, pastikan surat dokter valid</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-yellow-600 mr-2">•</span>
                            <span>Alasan penolakan WAJIB diisi untuk transparansi</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>