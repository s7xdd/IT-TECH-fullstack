@extends('layouts.app')
@section('title', 'Order Success - ' . env('APP_NAME', ''.env('APP_NAME')))
@section('content')
    <div class="container mx-auto py-12 px-6 lg:px-12">
        <div class="bg-white rounded-lg shadow-lg p-3 lg:p-6 max-w-md mx-auto ">
            <div class="text-center">
                <svg class="w-[100px] mx-auto mb-5" version="1.1" id="fi_190411" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 507.2 507.2"
                    style="enable-background:new 0 0 507.2 507.2;" xml:space="preserve">
                    <circle style="fill:#32BA7C;" cx="253.6" cy="253.6" r="253.6"></circle>
                    <path style="fill:#0AA06E;"
                        d="M188.8,368l130.4,130.4c108-28.8,188-127.2,188-244.8c0-2.4,0-4.8,0-7.2L404.8,152L188.8,368z">
                    </path>
                    <g>
                        <path style="fill:#FFFFFF;" d="M260,310.4c11.2,11.2,11.2,30.4,0,41.6l-23.2,23.2c-11.2,11.2-30.4,11.2-41.6,0L93.6,272.8
                       c-11.2-11.2-11.2-30.4,0-41.6l23.2-23.2c11.2-11.2,30.4-11.2,41.6,0L260,310.4z"></path>
                        <path style="fill:#FFFFFF;" d="M348.8,133.6c11.2-11.2,30.4-11.2,41.6,0l23.2,23.2c11.2,11.2,11.2,30.4,0,41.6l-176,175.2
                       c-11.2,11.2-30.4,11.2-41.6,0l-23.2-23.2c-11.2-11.2-11.2-30.4,0-41.6L348.8,133.6z"></path>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                </svg>
                <h2 class="text-2xl font-bold mb-2">Order Successfully Completed!</h2>
                <p class="text-gray-600 mb-4">Thank you for your purchase. Your order details are below.</p>
            </div>
            <div class="border-t border-gray-200 mt-4 pt-4">
                <div class="mb-2">
                    <span class="font-semibold">Order ID : </span>
                    <span> {{ $order->code }}</span>
                </div>
                <div class="mb-2">
                    <span class="font-semibold">Order Date : </span>
                    <span> {{ $order->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <div class="mb-2">
                    <span class="font-semibold">Total Amount : </span>
                    <span> {{ env('DEFAULT_CURRENCY') }} {{ $order->grand_total }}</span>
                </div>
                <div class="mb-2">
                    <span class="font-semibold">Shipping Address : </span>
                    <span>
                        @php
                            $shipping_address = json_decode($order->shipping_address);
                        @endphp

                        @if ($shipping_address)
                           {{ $shipping_address->name }}<br>
                           {{ $shipping_address->address }},
                           {{ $shipping_address->city }}, {{ $shipping_address->state }}<br>
                           {{ $shipping_address->country }} - {{ $shipping_address->zipcode }}<br>
                        @endif
                    </span>
                </div>
                <hr>
            </div>
            <div class="text-center mt-6">
                <div class="flex gap-2">
                    @if (auth()->user())
                        <a class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-bold text-center text-[#41b6e8] border border-[#41b6e8] transition-all duration-200 hover:text-[#000000]  hover:bg-[#41b6e8] "
                            href="{{ route('order-details', ['code' => $order->code]) }}">Track Order</a>
                    @endif

                    <a class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-bold text-white transition-all border  border-[#41b6e8] duration-200 bg-[#41b6e8]  hover:text-[#000000]  hover:bg-[#41b6e8]"
                        href="{{ route('products.index') }}">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
@endsection
