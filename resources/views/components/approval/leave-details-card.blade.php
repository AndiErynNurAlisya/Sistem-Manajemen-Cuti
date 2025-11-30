{{-- resources/views/components/approval/leave-details-card.blade.php --}}
{{-- Reusable component untuk menampilkan detail pengajuan cuti --}}

@props(['leaveRequest'])

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b" style="background-color: #f9fafb; border-color: #b5b89b;">
        <h3 class="text-lg font-semibold" style="color: #334124;">Detail Pengajuan Cuti</h3>
    </div>

    <div class="p-6 space-y-6">
        
        {{-- Leave Type & Duration Highlight --}}
        <div class="bg-gray-50 rounded-lg p-6 border-l-4" style="border-color: #566534;">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600 font-medium">Jenis Cuti</span>
                <x-ui.leave-type-badge :type="$leaveRequest->leave_type" class="text-base px-4 py-2" />
            </div>
            
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                <div>
                    <div class="text-xs text-gray-500 mb-1">Tanggal Mulai</div>
                    <div class="text-lg font-bold" style="color: #334124;">
                        {{ $leaveRequest->start_date->format('d M Y') }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 mb-1">Tanggal Selesai</div>
                    <div class="text-lg font-bold" style="color: #334124;">
                        {{ $leaveRequest->end_date->format('d M Y') }}
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 font-medium">Total Durasi</span>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-bold text-white" style="background-color: #566534;">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        {{ $leaveRequest->total_days }} Hari
                    </span>
                </div>
            </div>
        </div>

        {{-- Reason --}}
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #334124;">Alasan Cuti</label>
            <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 leading-relaxed border" style="border-color: #b5b89b;">
                {{ $leaveRequest->reason }}
            </div>
        </div>

        {{-- Contact Info Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: #334124;">Alamat Selama Cuti</label>
                <div class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3 border" style="border-color: #e5e7eb;">
                    {{ $leaveRequest->address_during_leave }}
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: #334124;">Nomor Darurat</label>
                <div class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3 border flex items-center" style="border-color: #e5e7eb;">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                    {{ $leaveRequest->emergency_contact }}
                </div>
            </div>
        </div>
    </div>
</div>