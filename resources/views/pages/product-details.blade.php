@extends('layouts.app')

@section('title', $response['name'] . ' - '.env('APP_NAME'))



@section('content')
<div class="container mx-auto py-6 lg:py-12 px-6 lg:px-12">
    <div class="flex flex-col md:flex-row gap-10">
        <!-- Product Image Slider -->
        <x-products.image-slider :product="$response" />

        <div class="w-full md:w-1/2">
            <h1 class="text-3xl font-semibold text-gray-800">{{ $response['name'] }}</h1>
            <p class="text-gray-600 mt-2 text-lg">
                {{ $response['category']['name'] }} | {{ $response['brand'] }} | SKU: <span class="font-semibold">{{ $response['sku'] }}</span>
            </p>

            <!-- Price & Discount -->
            <div class="flex items-center gap-3 mt-4">
                <span class="text-3xl font-bold text-primary">{{ env('DEFAULT_CURRENCY') }} {{ $response['main_price'] }}</span>
               
                @if ($response['main_price'] != $response['stroked_price'])
                    <span class="text-3xl font-bold text-gray-500 line-through">{{ env('DEFAULT_CURRENCY') }} {{ $response['stroked_price'] }}</span>
                @endif
                
                @if ($response['offer_tag'] != '')
                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-md">
                        {{ $response['offer_tag'] }}
                    </span>
                @endif
            </div>

            <!-- Short Description Before Add to Cart -->
            <x-products.short-description :product="$response" />

            <!-- Action Buttons -->
            <div class="mt-6 flex items-center gap-4">

                @php
                    $checkCartStatus = checkCartProduct($product->sku, $product->slug);
                @endphp
                <!-- Add to Cart Button (70% Width) -->
                <div  id="detailsCartButton">
                    @if ($checkCartStatus)
                        <a href="{{ route('cart') }}" class="bg-primary whitespace-nowrap text-white px-6 py-3 rounded-lg text-lg font-medium hover:bg-secondary transition-all w-[70%] lg:w-[50%] text-center">
                            Go to Cart
                        </a>
                    @else
                        @if ($response['quantity'] != 0)
                            <a href="#" class="add-to-cart-btn  bg-primary whitespace-nowrap text-white px-6 py-3 rounded-lg text-lg font-medium hover:bg-secondary transition-all w-[70%] lg:w-[50%] text-center"   data-product-slug="{{$product->slug}}" data-product-sku="{{ $product->sku}}">
                                Add to Cart
                            </a>
                        @else
                            <span class=" text-xl text-danger" >
                                Out Of Stock
                            </span>
                        @endif
                        
                    @endif
                
                </div>
                
                <!-- Wishlist Button -->
                @if (Auth::id())
                    @php
                        $isWishlisted = isWishlisted($product->id);
                    @endphp
                    <button class="wishlist-btn bg-gray-200 w-[45px] h-[45px] flex justify-center items-center rounded-full hover:bg-primary transition-all duration-300 transform hover:scale-105"  data-product-slug="{{ $product->slug }}" data-product-sku="{{ $product->sku }}">
                        <svg class="@if($isWishlisted) text-red-500 @endif h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="@if($isWishlisted) red @else none @endif" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z" />
                        </svg>
                    </button>
                @endif
                
            </div>

            <!-- Social Share -->
            <div class="mt-10">
                <x-products.social-share :product="$response"  />
            </div>

        </div>
    </div>

    <!-- Tabs Section -->
    <x-products.product-tabs :product="$response" />

    <!-- Related Products -->
    <x-products.related-products :relatedProducts="$relatedProducts" :lang="$lang" />


</div>
@endsection
