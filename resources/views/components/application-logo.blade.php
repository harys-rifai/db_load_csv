@props(['type' => 'login'])

@php
    $logoSrc = asset('pru30.png');
    $baseClasses = 'w-auto max-w-full transition-transform duration-700 ease-in-out hover:scale-110 animate-spin';
@endphp

@if ($type === 'login')
    <img 
        src="{{ $logoSrc }}" 
        alt="Login Logo" 
        {{ $attributes->merge([
            'class' => 'h-12 ' . $baseClasses
        ]) }}
    >
@elseif ($type === 'header')
    <img 
        src="{{ $logoSrc }}" 
        alt="Header Logo" 
        {{ $attributes->merge([
            'class' => 'h-8 ' . $baseClasses
        ]) }}
    >
@endif
