@props([
    'title' => 'Judul',
    'subtitle' => null,
])

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">
        {{ $title }}
    </h1>

    @if($subtitle)
        <p class="text-sm text-gray-600 mt-1">
            {{ $subtitle }}
        </p>
    @endif
</div>
