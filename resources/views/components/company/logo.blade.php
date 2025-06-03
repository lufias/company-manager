@props(['company', 'size' => 'w-32 h-32', 'shadow' => 'shadow-lg'])

@if($company->logo)
<img src="{{ str_starts_with($company->logo, 'http') ? $company->logo : asset('storage/' . $company->logo) }}"
    alt="{{ $company->name }} Logo"
    {{ $attributes->merge(['class' => $size . ' rounded-lg object-cover ' . $shadow]) }}>
@else
<img src="{{ asset('images/ImagePlaceholder.png') }}" 
    alt="No Logo" 
    {{ $attributes->merge(['class' => $size . ' rounded-lg object-cover ' . $shadow]) }} />
@endif