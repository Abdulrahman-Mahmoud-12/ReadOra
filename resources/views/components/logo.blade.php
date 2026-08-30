@props([
    'variant' => 'auto',
])

@php
    $showDark = $variant === 'dark';
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    @if($showDark)
        <img src="{{ asset('images/branding/logo-dark.svg') }}" alt="ReadOra" class="h-full">
    @else
        <img src="{{ asset('images/branding/logo.svg') }}" alt="ReadOra" class="h-full dark:hidden">
        <img src="{{ asset('images/branding/logo-dark.svg') }}" alt="ReadOra" class="h-full hidden dark:block">
    @endif
</div>
