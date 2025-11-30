<x-navigation.menu-item href="{{ route('hrd.dashboard') }}">
    <x-slot:icon>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </x-slot:icon>
    Dashboard
</x-navigation.menu-item>

<x-navigation.menu-item href="{{ route('hrd.final-approvals.index') }}">
    <x-slot:icon>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </x-slot:icon>
    Final Approvals
</x-navigation.menu-item>

{{-- Divider --}}
<div class="my-3 border-t border-gray-200"></div>

<div class="px-3 py-2" 
     x-data 
     x-show="!$store.sidebar.collapsed" 
     x-transition>
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
        Reports & Data
    </p>
</div>

<x-navigation.menu-item href="{{ route('hrd.history-cuti.index') }}">
    <x-slot:icon>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </x-slot:icon>
    History Cuti
</x-navigation.menu-item>

