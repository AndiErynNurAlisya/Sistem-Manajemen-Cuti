<x-app-layout>
    <x-slot name="pageTitle">Apply for Leave</x-slot>
    <x-slot name="pageDescription">Form Pengajuan Cuti</x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <x-forms.leave-request-form 
                        :submitRoute="route('employee.leave-requests.store')"
                        :can-request-annual="$canRequestAnnual"
                        :default-leave-type="$canRequestAnnual ? 'annual' : 'sick'"
                        submit-text="Ajukan Cuti"
                        :cancel-route="route('employee.leave-requests.index')"
                    />
            
        </div>
    </div>
</x-app-layout>