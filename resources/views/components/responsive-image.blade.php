@props(['image', 'alt' => '', 'class' => '', 'sizes' => '(max-width: 640px) 100vw, (max-width: 768px) 50vw, 33vw'])

@php
    $responsiveUrls = $image->responsive_urls ?? [];
    $defaultUrl = $image->url ?? '';
@endphp

<picture>
    {{-- Mobile --}}
    <source
        media="(max-width: 640px)"
        srcset="{{ $responsiveUrls['mobile'] ?? $defaultUrl }}"
    >

    {{-- Tablet --}}
    <source
        media="(max-width: 1024px)"
        srcset="{{ $responsiveUrls['tablet'] ?? $defaultUrl }}"
    >

    {{-- Desktop (default) --}}
    <img
        src="{{ $responsiveUrls['desktop'] ?? $defaultUrl }}"
        alt="{{ $alt }}"
        class="{{ $class }}"
        loading="lazy"
        sizes="{{ $sizes }}"
    >
</picture>