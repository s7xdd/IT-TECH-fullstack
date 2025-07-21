@extends('layouts.app')
@section('title', 'Cart - '.env('APP_NAME'))
@section('content')
    <section class="container mx-auto py-4 px-2 lg:px-12">
        <div class="mx-auto px-4 2xl:px-0">
            <!-- Section Title -->
            <h2 class="text-3xl md:text-4xl text-left">
                My Cart</span>
            </h2>
            <div class="mt-6 sm:mt-8 md:gap-6 xl:flex flex-wra lg:items-start xl:gap-8">
                <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-3xl">
                    <div class="space-y-6">

                     <x-cart-items :items="$response"/>

                    </div>
                </div>

                <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full lg:mt-4  xl:mt-0">
                     {{-- Apply coupon code --}}
                     <x-coupon-apply :response="$response"/>


                    <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm   sm:p-6">
                        <x-order-summary :response="$response"/>
                        <a href="{{ route('checkout') }}"
                            class="flex w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 bg-[#41b6e8] py-4">Proceed
                            to Checkout</a>
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> or </span>
                            <a href="{{ route('products.index') }}" title="" class="inline-flex items-center gap-2 text-sm font-medium text-primary-700 underline hover:no-underline dark:text-primary-500">
                                Continue Shopping
                                 <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                       fill="none" viewBox="0 0 24 24">
                                       <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                                 </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')

<script>
   document.addEventListener("DOMContentLoaded", function() {

   });
</script>
@endsection
