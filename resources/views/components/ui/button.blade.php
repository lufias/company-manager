@props(['href' => null])

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'bg-primary-orange text-white font-semibold px-6 py-2 rounded-md shadow-md hover:bg-[#d45a3c] transition']) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => 'bg-primary-orange text-white font-semibold px-6 py-2 rounded-md shadow-md hover:bg-[#d45a3c] transition']) }}>
        {{ $slot }}
    </button>
@endif
