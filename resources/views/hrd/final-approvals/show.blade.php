<x-app-layout>
    <x-slot name="pageTitle">Approval Requests</x-slot>
    <x-slot name="pageDescription">Review dan setujui cuti karyawan </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="mb-6">
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <a href="{{ route('hrd.final-approvals.index') }}" class="hover:text-gray-700">Final Approval</a>
                    <span>/</span>
                    <span class="text-gray-900">Detail</span>
                </div>
            </div>


            <x-approval.employee-card :leaveRequest="$leaveRequest" />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <x-approval.leave-details-card :leaveRequest="$leaveRequest" />

                    @php
                        $leaderApproval = $leaveRequest->approvals->where('approver_role', 'leader')->first();
                    @endphp

                    @if($leaderApproval)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b" style="background-color: #f9fafb; border-color: #b5b89b;">
                                <h3 class="text-lg font-semibold flex items-center" style="color: #334124;">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Riwayat Persetujuan Leader
                                </h3>
                            </div>
                            
                            <div class="p-6">
                                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <h4 class="text-sm font-semibold text-green-900 mb-2">
                                                Disetujui oleh Ketua Divisi
                                            </h4>
                                            <dl class="grid grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <dt class="text-xs text-green-700 font-medium">Nama Leader:</dt>
                                                    <dd class="text-green-900 font-semibold">{{ $leaderApproval->approver->full_name }}</dd>
                                                </div>
                                                <div>
                                                    <dt class="text-xs text-green-700 font-medium">Tanggal Approval:</dt>
                                                    <dd class="text-green-900 font-semibold">{{ $leaderApproval->approved_at->format('d M Y, H:i') }}</dd>
                                                </div>
                                            </dl>
                                            @if($leaderApproval->notes)
                                                <div class="mt-3">
                                                    <p class="text-xs text-green-700 font-medium mb-1">Catatan Leader:</p>
                                                    <div class="bg-white rounded-lg p-3 text-sm text-gray-700 border border-green-200">
                                                        {{ $leaderApproval->notes }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="lg:col-span-1 space-y-6">
                    
                    <div class="lg:sticky lg:top-6 space-y-6">
                        <x-approval.documents-card :leaveRequest="$leaveRequest" />

                        <x-approval.action-form 
                            :leaveRequest="$leaveRequest"
                            :approveRoute="route('hrd.final-approvals.approve', $leaveRequest)"
                            :rejectRoute="route('hrd.final-approvals.reject', $leaveRequest)"
                            :backRoute="route('hrd.final-approvals.index')"
                        />

                        
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>