@props(['type' => 'button', 'variant' => 'primary'])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn app-btn btn-'.$variant]) }}>
    {{ $slot }}
</button>
