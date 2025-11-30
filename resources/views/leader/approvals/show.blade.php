<x-app-layout>
    <x-slot name="pageTitle">Approval Requests</x-slot>
    <x-slot name="pageDescription">Review pengajuan cuti dari anggota divisi {{ auth()->user()->division->name }}</x-slot>

    <div class="py-6s">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="mb-6">
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <a href="{{ route('leader.approvals.index') }}" class="hover:text-gray-700">Approval Requests</a>
                    <span>/</span>
                    <span class="text-gray-900">Detail</span>
                </div>
            </div>

            <x-approval.employee-card :leaveRequest="$leaveRequest" />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <x-approval.leave-details-card :leaveRequest="$leaveRequest" />

                </div>

                <div class="lg:col-span-1 space-y-6">
                    <x-approval.documents-card :leaveRequest="$leaveRequest" />

                    <x-approval.action-form 
                        :leaveRequest="$leaveRequest"
                        :approveRoute="route('leader.approvals.approve', $leaveRequest)"
                        :rejectRoute="route('leader.approvals.reject', $leaveRequest)"
                        :backRoute="route('leader.approvals.index')"
                    />
                    
                </div>
            </div>

        </div>
    </div>
</x-app-layout>