@props([
    'href' => '',
    'label' => '← Kembali',
])

<a 
    href="{{ $href }}" 
    {{ $attributes->merge([
        'class' => 'text-sm text-gray-600 hover:text-gray-900 inline-block'
    ]) }}
>
    {{ $label }}
</a>
