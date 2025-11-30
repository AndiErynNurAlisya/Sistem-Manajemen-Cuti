{{-- resources/views/components/ui/sortable-header.blade.php --}}

{{-- 
    Component untuk Table Header yang bisa di-sort
    
    Usage:
    <x-ui.sortable-header 
        column="full_name" 
        label="Nama"
        :sortBy="request('sort_by')"
        :sortOrder="request('sort_order')" />
--}}

@props([
    'column',           // Nama kolom di database
    'label',            // Label yang ditampilkan
    'sortBy' => null,   // Current sort column dari request
    'sortOrder' => 'asc' // Current sort order dari request
])

@php
    // Check apakah kolom ini sedang aktif di-sort
    $isActive = $sortBy === $column;
    
    // Tentukan order berikutnya (toggle)
    $nextOrder = ($isActive && $sortOrder === 'asc') ? 'desc' : 'asc';
    
    // Build URL dengan merge semua params kecuali sort
    $sortUrl = route(request()->route()->getName(), array_merge(
        request()->except(['sort_by', 'sort_order', 'page']),
        [
            'sort_by' => $column,
            'sort_order' => $nextOrder
        ]
    ));
@endphp

<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
    <a href="{{ $sortUrl }}" 
       class="flex items-center space-x-1 hover:text-gray-700 group transition-colors">
        <span>{{ $label }}</span>
        
        {{-- Sort Icon --}}
        @if($isActive)
            {{-- Active sort indicator --}}
            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if($sortOrder === 'asc')
                    {{-- Up arrow for ASC --}}
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                @else
                    {{-- Down arrow for DESC --}}
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                @endif
            </svg>
        @else
            {{-- Inactive sort indicator --}}
            <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
        @endif
    </a>
</th>