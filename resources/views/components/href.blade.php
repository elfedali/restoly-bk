{{-- href component --}}
@props(['href' => '#', 'text' => ''])

<a href="{{ $href }}" class="text-indigo-600 hover:text-indigo-900">
    {{ $text }}
</a>
