{{-- This is a checkbox input component. --}}
@props(['disabled' => false, 'checked' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm',
]) !!} type="checkbox" {{ $checked ? 'checked' : '' }}>
