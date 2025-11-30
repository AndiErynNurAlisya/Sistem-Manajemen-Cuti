

@props(['leaveRequest'])

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="bg-gradient-to-b from-[#334124]  to-[#b5b89b]  px-6 py-4">
        <h2 class="text-lg font-semibold text-white">Informasi Cuti</h2>
    </div>
    <div class="p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-sm font-semibold text-gray-900 mb-1">Jenis Cuti</dt>
                <dd class="text-sm text-gray-500 font-medium">{{ $leaveRequest->leave_type->label() }}</dd>
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
                <dt class="text-sm font-semibold text-gray-900  mb-1">Tanggal Selesai</dt>
                <dd class="text-sm text-gray-500 font-medium">{{ $leaveRequest->end_date->format('d F Y') }}</dd>
            </div>

            <div>
                <dt class="text-sm font-semibold text-gray-900 mb-1">Total Hari</dt>
                <dd class=" text-gray-500 font-medium">
                        {{ $leaveRequest->total_days }} hari kerja 
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500 mb-1">Divisi</dt>
                <dd class="text-sm text-gray-900">{{ $leaveRequest->user->division->name ?? '-' }}</dd>
            </div>
        </dl>
    </div>
</div>