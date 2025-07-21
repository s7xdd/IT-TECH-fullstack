@props(['product','lang'])
    @php
        $priceData = getProductOfferPrice($product);
    @endphp
    <div class="relative group cursor-pointer bg-transparent transition duration-300 overflow-hidden rounded-lg">
        <a href="{{ route('products.show',['slug' => $product->slug]) }}">
            <img src="{{ get_product_image($product->thumbnail_img,'300') }}"  alt="{{ $product->getTranslation('name', $lang) }}"  class="w-full object-cover rounded-lg transition-transform duration-[400ms] group-hover:scale-[1.05]">
        </a>

        <div class="static xl:absolute bottom-[33px] left-[10px] right-[10px] flex justify-between items-center py-[6px] bg-transparent z-[1]">
            <span class="text-[#41B6E8] py-[7px] px-[10px] rounded-full font-semibold text-sm shadow-sm bg-white/80 backdrop-blur-sm">
                {{ env('DEFAULT_CURRENCY').' '.$priceData['discounted_price'] }}
                @if ($priceData['discounted_price'] != $priceData['original_price'])
                    <span class="text-gray-500 line-through"> &nbsp;{{ env('DEFAULT_CURRENCY').' '.$priceData['original_price'] }}</span>
                @endif
            </span>
            
                <x-buttons.add_to_cart :slug="$product->slug" :sku="$product->sku"/>
           
        </div>
        
        <a href="{{ route('products.show',['slug' => $product->slug]) }}">
            <p class="mt-[15px] text-left text-sm font-medium text-gray-800">{{ $product->getTranslation('name', $lang) }}</p>
        </a>
       
    </div>
