{{-- resources/views/components/ui/filter-card.blade.php --}}
@props([
    'action',
    'type' => 'leave-request',
    'divisions' => [],
    'leaders' => [],
])

@php
    $buttonColor = '#334124';
    $buttonHover = '#566534';
@endphp

<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form method="GET" action="{{ $action }}" class="space-y-4">

        {{-- ================== LEAVE REQUEST ================== --}}
        @if($type === 'leave-request')
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-6">

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium mb-1 text-[#334124]">Status</label>
                    <select name="status"
                        class="w-full border-gray-300 rounded-md text-sm focus:ring-[#334124] focus:border-[#334124]">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved_by_leader" {{ request('status')=='approved_by_leader' ? 'selected' : '' }}>Disetujui Ketua</option>
                        <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Ditolak</option>
                        <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                {{-- Jenis Cuti --}}
                <div>
                    <label class="block text-sm font-medium mb-1 text-[#334124]">Jenis Cuti</label>
                    <select name="leave_type"
                        class="w-full border-gray-300 rounded-md text-sm focus:ring-[#334124] focus:border-[#334124]">
                        <option value="">Semua Jenis</option>
                        <option value="annual" {{ request('leave_type')=='annual' ? 'selected' : '' }}>Cuti Tahunan</option>
                        <option value="sick" {{ request('leave_type')=='sick' ? 'selected' : '' }}>Cuti Sakit</option>
                    </select>
                </div>

                {{-- Dari --}}
                <div>
                    <label class="block text-sm font-medium mb-1 text-[#334124]">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full border-gray-300 rounded-md text-sm focus:ring-[#334124] focus:border-[#334124]">
                </div>

                {{-- Sampai --}}
                <div>
                    <label class="block text-sm font-medium mb-1 text-[#334124]">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full border-gray-300 rounded-md text-sm focus:ring-[#334124] focus:border-[#334124]">
                </div>

            </div>
        @endif


        {{-- ================== FILTER BUTTONS ================== --}}
        <div class="flex flex-wrap items-center gap-3 pt-2">

            {{-- Button Filter --}}
            <button type="submit"
                class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg shadow-sm transition"
                style="background-color: {{ $buttonColor }};"
                onmouseover="this.style.backgroundColor='{{ $buttonHover }}'"
                onmouseout="this.style.backgroundColor='{{ $buttonColor }}'">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Filter
            </button>

            {{-- RESET --}}
            @php
                $hasFilters = request()->except(['page', 'sort_by', 'sort_order']) != [];
            @endphp

            @if($hasFilters)
                <a href="{{ $action }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reset
                </a>
            @endif
        </div>

    </form>
</div>
