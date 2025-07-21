@props(['product'])

@php
    $currentUrl = urlencode(url()->current());
    $pageTitle = urlencode($product['name'] ?? 'Check this product');
    $productImage = $product['thumbnail_image'];
@endphp

<div class="flex flex-wrap xl:flex-nowrap items-center gap-3 mt-4">
    <span class="text-gray-700 text-sm font-semibold">Share:</span>

    <!-- Facebook Share -->
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $currentUrl }}" target="_blank"
        class="flex items-center gap-2 px-3 py-2 bg-gray-200 rounded-full hover:bg-gray-300 transition-all duration-300">
       
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
            <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 5 3.66 9.14 8.43 9.88v-6.99h-2.54v-2.89h2.54V9.31c0-2.51 1.49-3.89 3.77-3.89 1.09 0 2.23.19 2.23.19v2.45h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.89h-2.34v6.99C18.34 21.14 22 17 22 12z"/>
        </svg>
        <span class="text-sm text-gray-700 hidden xl:block">Facebook</span>
    </a>

    <!-- Twitter Share -->

    <a href="https://twitter.com/intent/tweet?url={{ $currentUrl }}&text={{ $pageTitle }}" target="_blank"
        class="flex items-center gap-2 px-3 py-2 bg-gray-200 rounded-full hover:bg-gray-300 transition-all duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
            <path d="M20.19 2H23L14.56 10.98L24 22H16.92L10.9 15.02L3.33 22H0.48L9.47 12.36L0 2H7.32L12.79 8.42L20.19 2ZM18.9 20H21.1L7.04 4H4.67L18.9 20Z"/>
        </svg>
        <span class="text-sm text-gray-700 hidden xl:block">Twitter</span>
    </a>

    <!-- Pinterest Share -->
            
    <a href="https://pinterest.com/pin/create/button/?url={{ $currentUrl }}&media={{ $productImage }}&description={{ $pageTitle }}" target="_blank"
        class="flex items-center gap-2 px-3 py-2 bg-gray-200 rounded-full hover:bg-gray-300 transition-all duration-300">
        
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
            <path d="M12 0C5.37 0 0 5.37 0 12c0 4.89 2.9 9.1 7.06 11.01-.1-.94-.2-2.39.04-3.42.22-.95 1.46-6.06 1.46-6.06s-.37-.75-.37-1.85c0-1.74 1.01-3.04 2.27-3.04 1.07 0 1.59.8 1.59 1.76 0 1.07-.68 2.67-1.03 4.14-.3 1.26.64 2.29 1.88 2.29 2.27 0 3.81-2.92 3.81-6.38 0-2.63-1.77-4.6-5-4.6-3.64 0-5.91 2.73-5.91 5.77 0 1.04.4 2.17.9 2.78.1.12.12.22.09.34-.1.37-.3 1.26-.34 1.44-.05.22-.18.27-.41.16-1.54-.71-2.51-2.93-2.51-4.72 0-3.83 2.78-7.36 8.01-7.36 4.2 0 7.47 2.99 7.47 7.02 0 4.17-2.62 7.52-6.26 7.52-1.22 0-2.36-.63-2.75-1.37l-.75 2.87c-.27 1.04-1 2.35-1.49 3.15C9.42 23.79 10.68 24 12 24c6.63 0 12-5.37 12-12S18.63 0 12 0z"/>
        </svg>
        <span class="text-sm text-gray-700 hidden xl:block">Pinterest</span>
    </a>

</div>
