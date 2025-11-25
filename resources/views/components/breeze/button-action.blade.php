@props(['href' => '#', 'color' => 'blue', 'confirm' => null, 'method' => 'GET'])

@if($method === 'GET')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "text-{$color}-600 hover:text-{$color}-900 transition"]) }}>
        {{ $slot }}
    </a>
@else
    <form action="{{ $href }}" method="POST" {{ $attributes->merge(['class' => 'inline']) }} onsubmit="{{ $confirm ? "return confirm('{$confirm}')" : '' }}">
        @csrf
        @method($method)
        <button type="submit" class="text-{{ $color }}-600 hover:text-{{ $color }}-900 transition">
            {{ $slot }}
        </button>
    </form>
@endif
