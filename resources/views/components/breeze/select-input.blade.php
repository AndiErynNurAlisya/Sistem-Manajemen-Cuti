{{-- resources/views/components/select-input.blade.php --}}

@props([
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'disabled' => false
])

<select 
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm disabled:bg-gray-100 disabled:cursor-not-allowed']) }}>
    
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    
    @foreach($options as $value => $label)
        <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>