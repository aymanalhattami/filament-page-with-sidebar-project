@props([
    'color' => 'gray',
    'text' => ''
])

<span class="inline-flex items-center rounded-full bg-{{ $color }}-100 px-2.5 py-0.5 mt- text-xs font-medium text-{{ $color }}-800">{{ $text }}</span>