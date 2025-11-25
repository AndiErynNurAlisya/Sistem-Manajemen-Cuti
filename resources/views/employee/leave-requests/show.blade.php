{{-- resources/views/employee/leave-requests/show.blade.php --}}
<x-app-layout>
    <x-slot name="title">Detail Pengajuan Cuti</x-slot>

    <div x-data="{ showCancelModal: false, cancellationReason: '' }">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('employee.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                <span>/</span>
                <a href="{{ route('employee.leave-requests.index') }}" class="hover:text-gray-700">My Leave Requests</a>
                <span>/</span>
                <span class="text-gray-900">Detail</span>
            </div>
            
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Pengajuan Cuti</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $leaveRequest->period }}</p>
                </div>
                
                {{-- Status Badge --}}
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'approved_by_leader' => 'bg-blue-100 text-blue-800',
                        'approved' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        'cancelled' => 'bg-gray-100 text-gray-800',
                    ];
                @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $statusColors[$leaveRequest->status->value] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $leaveRequest->status->label() }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Leave Information --}}
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white">Informasi Cuti</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-1">Jenis Cuti</dt>
                                <dd>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $leaveRequest->leave_type->value === 'annual' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $leaveRequest->leave_type->label() }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-1">Tanggal Pengajuan</dt>
                                <dd class="text-sm text-gray-900">{{ $leaveRequest->request_date->format('d F Y') }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-1">Tanggal Mulai</dt>
                                <dd class="text-sm text-gray-900 font-semibold">{{ $leaveRequest->start_date->format('d F Y') }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-1">Tanggal Selesai</dt>
                                <dd class="text-sm text-gray-900 font-semibold">{{ $leaveRequest->end_date->format('d F Y') }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-1">Total Hari</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $leaveRequest->total_days }} hari kerja
                                    </span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-1">Divisi</dt>
                                <dd class="text-sm text-gray-900">{{ $leaveRequest->user->division->name ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Leave Details --}}
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Detail Pengajuan</h2>
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

                        {{-- Medical Certificate --}}
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

                        {{-- Rejection/Cancellation Reason --}}
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

                {{-- Approval Timeline --}}
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Timeline Approval</h2>
                    </div>
                    <div class="p-6">
                        @if($leaveRequest->approvals->isEmpty())
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-gray-500">Menunggu approval</p>
                            </div>
                        @else
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    @foreach($leaveRequest->approvals as $approval)
                                        <li>
                                            <div class="relative pb-8">
                                                @if(!$loop->last)
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        @if($approval->status === 'approved')
                                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </span>
                                                        @else
                                                            <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div>
                                                            <div class="text-sm">
                                                                <span class="font-medium text-gray-900">{{ $approval->approver->full_name }}</span>
                                                                <span class="text-gray-500 ml-2">({{ ucfirst($approval->approver_role) }})</span>
                                                            </div>
                                                            <p class="mt-0.5 text-sm text-gray-500">
                                                                {{ $approval->approved_at ? $approval->approved_at->format('d F Y, H:i') : '-' }}
                                                            </p>
                                                        </div>
                                                        @if($approval->status === 'approved')
                                                            <div class="mt-2 text-sm text-green-700 bg-green-50 rounded-md p-3">
                                                                <p class="font-medium">✓ Disetujui</p>
                                                                @if($approval->notes)
                                                                    <p class="mt-1 text-green-600">Catatan: {{ $approval->notes }}</p>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <div class="mt-2 text-sm text-red-700 bg-red-50 rounded-md p-3">
                                                                <p class="font-medium">✗ Ditolak</p>
                                                                @if($approval->notes)
                                                                    <p class="mt-1 text-red-600">Alasan: {{ $approval->notes }}</p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar Actions --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Actions Card --}}
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h3>
                    <div class="space-y-3">
                        {{-- Download Request Letter --}}
                        @if($leaveRequest->leave_type->value === 'annual' && $leaveRequest->request_letter_pdf && fileExists($leaveRequest->request_letter_pdf))
                            <a href="{{ route('employee.leave-requests.download-request', $leaveRequest) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-indigo-300 rounded-md shadow-sm text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Surat Permohonan
                            </a>
                        @endif

                        {{-- Download Approval Letter --}}
                        @if($leaveRequest->status->value === 'approved' && $leaveRequest->approval_letter_pdf && fileExists($leaveRequest->approval_letter_pdf))
                            <a href="{{ route('employee.leave-requests.download-approval', $leaveRequest) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Surat Izin Cuti
                            </a>
                        @endif

                        {{-- Cancel Button --}}
                        @if($leaveRequest->canBeCancelled())
                            <button @click="showCancelModal = true"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batalkan Cuti
                            </button>
                        @endif

                        {{-- Back Button --}}
                        <a href="{{ route('employee.leave-requests.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>

                {{-- Status Info --}}
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                @if($leaveRequest->status->value === 'pending')
                                    Pengajuan sedang menunggu approval dari Ketua Divisi.
                                @elseif($leaveRequest->status->value === 'approved_by_leader')
                                    Pengajuan sudah disetujui Ketua Divisi, menunggu approval HRD.
                                @elseif($leaveRequest->status->value === 'approved')
                                    Pengajuan cuti Anda telah disetujui! Silakan download surat izin.
                                @elseif($leaveRequest->status->value === 'rejected')
                                    Pengajuan cuti ditolak. Anda bisa mengajukan cuti baru.
                                @else
                                    Pengajuan cuti dibatalkan.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cancel Modal --}}
        <div x-show="showCancelModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCancelModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                     @click="showCancelModal = false">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="showCancelModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <form method="POST" action="{{ route('employee.leave-requests.cancel', $leaveRequest) }}">
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
                                    :disabled="cancellationReason.length < 10"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                Batalkan Cuti
                            </button>
                            <button type="button"
                                    @click="showCancelModal = false"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>