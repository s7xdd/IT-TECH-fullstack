@extends('layouts.app')

@section('title', 'Wishlist - '.env('APP_NAME'))

@section('content')

    <div class="container mx-auto py-12 px-6 lg:px-12">
        <!-- Section Title -->
        <h2 class="text-3xl md:text-4xl text-left">
            My Wishlist <span class="">({{ wishlistCount() }} Items)</span>
        </h2>

        <!-- Products Grid -->
        <div class="">
            @if (!empty($result[0]))
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-5 2xl:grid-cols-5 gap-x-[20px] gap-y-[30px] mt-[40px]">
                    @foreach ($result as $res)
                        @php
                            $checkCartStatus = checkCartProduct($res['product']['sku'], $res['product']['slug']);
                            $isWishlisted = isWishlisted($res['product']['product_id']);
                        @endphp
                        <div class="relative group cursor-pointer bg-transparent transition duration-300 overflow-hidden rounded-lg">
                            
                            <svg class="wish-list-btn @if($isWishlisted) text-red-500 @endif absolute top-4 right-4 z-50   text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="34" height="34" fill="@if($isWishlisted) red @else none @endif" viewBox="0 0 24 24"  data-product-slug="{{ $res['product']['slug'] }}" data-product-sku="{{ $res['product']['sku'] }}">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z" />
                            </svg>
                        
                            <a href="{{ route('products.show',['slug' => $res['product']['slug']]) }}" class="block">
                                <div class="overflow-hidden rounded-lg">
                                    <img src="{{ $res['product']['thumbnail_image']}}"
                                        alt="{{$res['product']['name']}}"
                                        class="w-full object-cover rounded-lg transition-transform duration-[400ms] ease-in-out group-hover:scale-[1.05]">
                                </div>
                            </a>

                            <div class="xl:absolute bottom-[55px] left-[10px] right-[10px] flex-wrap xl:flex-nowrap flex justify-between items-center py-[6px] bg-transparent z-[1]">
                                <span
                                    class="text-[#41B6E8] py-[7px] px-[10px] rounded-full font-semibold text-sm shadow-sm bg-white/80 backdrop-blur-sm">
                                    {{ env('DEFAULT_CURRENCY').' '.$res['product']['main_price'] }}
                                    @if ($res['product']['main_price'] != $res['product']['stroked_price'])
                                        <br>
                                        <span class="text-gray-500 line-through text-xs"> &nbsp;{{ env('DEFAULT_CURRENCY') }} {{$res['product']['stroked_price']}}</span>
                                    @endif
                                </span>

                                @if (!$checkCartStatus)
                                    <a class="add-to-cart-btn-wishlist bg-[#41B6E8] py-[7px] px-[10px] rounded-full font-semibold text-sm shadow-sm text-white backdrop-blur-sm "  data-product-slug="{{$res['product']['slug']}}" data-product-sku="{{ $res['product']['sku']}}">
                                        
                                        Move to Cart
                                    </a>
                                @else
                                    <a href="{{ route('cart') }}" class="bg-[#22a914] py-[7px] px-[10px] rounded-full font-semibold text-sm shadow-sm text-white backdrop-blur-sm " >
                                        Go to Cart
                                    </a>
                                @endif

                                {{-- <button
                                    class="w-[35px] h-[35px] flex items-center justify-center bg-secondary hover:bg-[#41B6E8] text-white hover:text-white rounded-full shadow-md transition duration-[300ms]"
                                    aria-label="Add to Cart">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" width="20"
                                        height="20">
                                        <path
                                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z">
                                        </path>
                                    </svg>
                                </button> --}}
                            </div>

                            <a href="{{ route('products.show',['slug' => $res['product']['slug']]) }}">
                                <p class="mt-[10px] text-left text-sm font-medium text-gray-800">{{$res['product']['name']}}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 h-14 w-14 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21c-.6 0-1.2-.2-1.7-.6l-6.2-5.6C2.1 13.2 1 11.2 1 9c0-2.7 2.1-4.9 4.8-4.9 1.6 0 3.1.8 4 2.1C10.1 4.9 11.6 4 13.2 4c2.7 0 4.8 2.2 4.8 4.9 0 2.2-1.1 4.2-3.1 5.8l-6.2 5.6c-.5.4-1.1.6-1.7.6z" />
                    </svg>
                    <p class="text-xl font-semibold text-gray-700">Your Wishlist is Empty</p>
                    <p class="text-sm mt-1">Start exploring and add items you love.</p>
                    <a href="{{ route('products.index') }}" class="mt-5 inline-block bg-primary hover:bg-primary text-white px-5 py-2 rounded-lg transition duration-300">
                        Browse Products
                    </a>
                </div>
            @endif
        </div>
    </div>


@endsection


@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $(document).on('click', '.add-to-cart-btn-wishlist', function () {
            const productSlug = $(this).data('product-slug');
            const productSku = $(this).data('product-sku');
            var quantity = $('#product_quantity').val() ?? 1;
        
            $.ajax({
                url: '/cart/add', // Laravel route
                type: 'POST',
                data: {
                    product_slug: productSlug,
                    sku : productSku,
                    quantity: quantity, // Default quantity
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {
                    $('.cart_count').text(response.cart_count);
                    $('.canvasCartcount').text(response.cart_count);
                    if (response.status == true) {
                        toastr.success(response.message, "success");
                    } else {
                        toastr.error(response.message, "error");
                    }
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                },
                error: function (xhr, status, error) {
                    toastr.error("{{trans('messages.product_add_cart_failed')}}", 'Error');
                },
            });
        });

        $(document).on('click', '.wish-list-btn', function () {
            const productSlug = $(this).data('product-slug');
            const productSku = $(this).data('product-sku');
            const url = '/wishlist/store';
            const wishButton = $(this);

            $.ajax({
                url: '/check-login-status',  // Endpoint to check login status
                type: 'GET',
                success: function (response) {
                    if (response.is_logged_in) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                productSlug: productSlug, 
                                productSku: productSku, 
                                _token: $('meta[name="csrf-token"]').attr('content') 
                            },
                            success: function(response) {
                                if(response.status == true){
                                    $('.wishlist_count').text(response.wishlist_count);
                                    toastr.success(response.message, "{{trans('messages.success')}}");
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 2000);
                                
                                }else{
                                    toastr.error(response.message, "{{trans('messages.error')}}");
                                }
                            },
                            error: function(xhr, status, error) {
                                toastr.error("{{trans('messages.wishlist_remove_error')}}", "{{trans('messages.error')}}");
                            }
                        });
                    } else {
                        // Show alert if not logged in
                        toastr.error("{{trans('messages.login_msg')}}", "{{trans('messages.error')}}");
                    }
                },
                error: function () {
                    toastr.error("{{trans('messages.error_try_again')}}", "{{trans('messages.error')}}");
                }
            });

        });
    });
</script>
@endsection
