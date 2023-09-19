{{-- href button --}}
@props(['href' => '#', 'text' => ''])

<a href="{{ $href }}"
    class="bg-blue-500 hover:bg-blue-700 text-dark font-bold py-2 px-4 rounded display-inline-block ">
    {{ $text }}
</a>
