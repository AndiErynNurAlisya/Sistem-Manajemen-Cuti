{{-- resources/views/leader/leave-requests/create.blade.php --}}
<x-app-layout>
    <x-slot name="pageTitle">Apply for Leave</x-slot>
    <x-slot name="pageDescription">Form Pengajuan Cuti</x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-forms.leave-request-form 
    :submitRoute="route('leader.leave-requests.store')" 
    :can-request-annual="true"
    default-leave-type="annual"
    submit-text="Ajukan Cuti"
    :cancel-route="route('leader.leave-requests.index')"
/>
        </div>
    </div>
</x-app-layout>