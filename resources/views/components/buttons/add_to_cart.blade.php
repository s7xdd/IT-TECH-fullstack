
@php
    $checkCartStatus = checkCartProduct($sku, $slug);
@endphp
<span class="cart_button_edit">
    <button class="add-to-cart-btn w-[35px] h-[35px] flex items-center justify-center @if ($checkCartStatus) bg-[#22a914] @else bg-secondary @endif hover:bg-[#41B6E8] text-white hover:text-white rounded-full shadow-md transition duration-[300ms]" aria-label="Add to Cart"  data-product-slug="{{$slug}}" data-product-sku="{{ $sku}}">
        @if ($checkCartStatus)
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="white">
                <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/>
            </svg>
        
        @else
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" width="20" height="20">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
        @endif
        
    </button>
</span>