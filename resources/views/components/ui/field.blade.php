@props(['label'])

<div class="space-y-1">
    <h3 class="font-semibold text-gray-700 text-sm">
        {{ $label }}:
    </h3>

    <div class="text-gray-900">
        {{ $slot }}
    </div>
</div>
