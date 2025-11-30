<!-- @props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label> -->

{{-- resources/views/components/breeze/input-label.blade.php --}}
@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-[#334124]']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-red-500 ml-1">*</span>
    @endif
</label>