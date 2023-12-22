@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge() }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
