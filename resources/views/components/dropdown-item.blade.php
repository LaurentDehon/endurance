@props([
    'type' => 'button',
    'icon' => null,
    'iconColor' => 'white', 
    'href' => null,
])

@php
    $baseClasses = 'w-full rounded-lg text-left px-4 py-2.5 text-white hover:bg-white hover:bg-opacity-10 flex items-center gap-2 transition-colors';
    $attrs = $attributes->merge(['class' => $baseClasses]);
@endphp

@if ($type === 'link' && $href)
    <a href="{{ $href }}" {{ $attrs }}>
        @if ($icon)
            <i class="fas fa-{{ $icon }} w-5 text-{{ $iconColor }}-400"></i>
        @endif
        <span class="text-sm">{{ $slot }}</span>
    </a>
@else
    <button {{ $attrs }}>
        @if ($icon)
            <i class="fas fa-{{ $icon }} w-5 text-{{ $iconColor }}-400"></i>
        @endif
        <span class="text-sm">{{ $slot }}</span>
    </button>
@endif