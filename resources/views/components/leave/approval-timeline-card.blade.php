<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="bg-gradient-to-b from-[#334124] to-[#b5b89b] px-6 py-4 border-b border-gray-200">
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
                                                <p class="font-medium"> Disetujui</p>
                                                @if($approval->notes)
                                                    <p class="mt-1 text-green-600">Catatan: {{ $approval->notes }}</p>
                                                @endif
                                            </div>
                                        @else
                                            <div class="mt-2 text-sm text-red-700 bg-red-50 rounded-md p-3">
                                                <p class="font-medium"> Ditolak</p>
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