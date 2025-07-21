
@if(!empty($items['products']))
    @foreach ($items['products'] as $key => $prod)

        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm   md:p-6" id="cart_item{{$prod['id']}}">
            <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                <a href="{{ route('products.show',['slug' => $prod['product']['slug']]) }}" class="shrink-0 md:order-1">
                    <img class="h-20 w-20 dark:block rounded-md" src="{{$prod['product']['image']}}" alt=" {{$prod['product']['name']}}" />
                </a>
                <label for="counter-input" class="sr-only">Choose quantity:</label>
                <div class="flex items-center justify-between md:order-3 md:justify-end ">
                    <div class="flex items-center border px-4 py-2 rounded-lg">
                       
                        <div class="quantity-control" data-product-id="{{ $prod['id'] }}">
                            <button type="button" class="change_quantity decrement-button inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-200"  data-action="minus" data-max-quantity="{{$prod['product']['max_qty']}}" data-id="{{$prod['id']}}">
                                <svg class="h-2.5 w-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                </svg>
                            </button>
                            <input type="text" class="counter-input w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0" id="quantity-field_{{$prod['id']}}" value="{{$prod['quantity']}}" required />
                            <button type="button" class="change_quantity increment-button inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-200"  data-action="plus" data-id="{{$prod['id']}}" data-max-quantity="{{$prod['product']['max_qty']}}">
                                <svg class="h-2.5 w-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="text-end md:order-4 md:w-32">
                        <p class="text-base text-lg font-semibold text-gray-900 text-black">
                            {{ env('DEFAULT_CURRENCY') }} {{$prod['main_price']}}
                            @if ($prod['main_price'] != $prod['stroked_price'])
                                <br>
                                <span class="text-gray-500 line-through text-xs"> &nbsp;{{ env('DEFAULT_CURRENCY') }} {{$prod['stroked_price']}}</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                    <a href="{{ route('products.show',['slug' => $prod['product']['slug']]) }}" class="text-base font-medium text-gray-900 hover:underline text-black">
                        {{$prod['product']['name']}}
                    </a>
                    @php
                        $isWishlisted = isWishlisted($prod['product']['id']);
                    @endphp
                    <div class="flex items-center gap-4">
                        @if (Auth::id())
                            <button type="button" class="wishlist-btn inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 hover:underline dark:text-gray-400" data-product-slug="{{ $prod['product']['slug'] }}" data-product-sku="{{ $prod['product']['sku'] }}">
                                <svg class="@if($isWishlisted) text-red-500 @endif me-1.5 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" fill="@if($isWishlisted) red @else none @endif" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z" />
                                </svg>
                                <span class="text-[11px] xl:text-[14px]">
                                    @if ($isWishlisted) 
                                        Remove from wishlist
                                    @else
                                        Add to wishlist
                                    @endif
                                </span>  
                                
                            </button>
                        @endif
                       
                        <button type="button"
                            class="remove-cart-item inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500" data-id="{{$prod['id']}}">
                            <svg class="me-1.5 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18 17.94 6M18 18 6.06 6" />
                            </svg>
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
<div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm   md:p-6">
    <div class="space-y-4 md:flex md:items-center md:justify-between text-center md:gap-6 md:space-y-0">
        <img src="{{ asset('assets/images/cart.png') }}"/>
    </div>
</div>
@endif
