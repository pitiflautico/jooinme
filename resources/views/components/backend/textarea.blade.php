@props(['disabled' => false, 'rows' => 4])

<textarea {{ $disabled ? 'disabled' : '' }} rows="{{ $rows }}" {!! $attributes->merge(['class' => 'form-control app-form-control']) !!}>{{ $slot }}</textarea>
