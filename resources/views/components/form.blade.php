@props([
    'post' => null,
])

@php

    $method = $post ? 'POST' : 'GET';

@endphp

<form {{ $attributes->class(['flex flex-col gap-4']) }} method="{{ $method }}">
    @csrf

    {{ $slot }}

</form>
