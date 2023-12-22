<button {{ $attributes->merge(['type' => 'button', 'class' => 'cancel-button']) }}>
    {{ $slot }}
</button>
