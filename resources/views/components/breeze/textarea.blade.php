@props([
    'label' => null,
    'name',
    'id' => $name,
    'value' => '',
])

<div class="mb-4">
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $attributes->merge([
            'class' =>
                'w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500'
        ]) }}
        rows="4"
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
