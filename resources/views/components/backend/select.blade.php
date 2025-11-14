@props(['disabled' => false, 'options' => []])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-select app-form-select']) !!}>
    {{ $slot }}
</select>
