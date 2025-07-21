@extends('layouts.app')
@section('title', 'Checkout - '.env('APP_NAME'))
@section('content')
    <section class="container mx-auto py-12 px-6 lg:px-12">
        <div class="mx-auto px-4 2xl:px-0">
            <!-- Section Title -->
            <h2 class="text-3xl md:text-4xl text-left">
                Checkout
            </h2>
            <div class="mt-6 sm:mt-4 md:gap-6 xl:flex lg:items-start xl:gap-4">
                <div class="mx-auto w-full flex-none lg:max-w-4xl xl:max-w-2xl 2xl:max-w-4xl">
                    @if (auth()->check())
                        <div class="bg-white border rounded-lg p-2 lg:p-4 mb-5">
                            <div class="flex gap-3">
                                <svg width="18pt" height="18pt" id="fi_15501313" enable-background="new 0 0 1024 1024"
                                    viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                                    <g id="XMLID_3_">
                                        <g id="XMLID_1_">
                                            <g id="XMLID_16_">
                                                <path id="XMLID_24_"
                                                    d="m695.2 275.5c0 9.8-.7 19.5-2 29.2.4-2.7.7-5.3 1.1-8-2.6 18.3-7.4 36.2-14.5 53.2 1-2.4 2-4.8 3-7.2-5.3 12.6-11.8 24.7-19.4 36-1.9 2.9-4 5.8-6.1 8.6-4.4 5.9 3.8-4.7.7-.9-1.1 1.4-2.2 2.7-3.3 4.1-4.2 5-8.6 9.9-13.2 14.5s-9.5 9.1-14.5 13.2c-1.3 1.1-2.7 2.2-4.1 3.3-3.9 3.1 6.8-5.1.9-.7-2.8 2.1-5.7 4.1-8.6 6.1-11.4 7.6-23.4 14-36 19.4l7.2-3c-17.1 7.1-34.9 12-53.2 14.5 2.7-.4 5.3-.7 8-1.1-19.4 2.6-38.9 2.6-58.3 0 2.7.4 5.3.7 8 1.1-18.3-2.6-36.2-7.4-53.2-14.5l7.2 3c-12.6-5.3-24.7-11.8-36-19.4-2.9-1.9-5.8-4-8.6-6.1-5.9-4.4 4.7 3.8.9.7-1.4-1.1-2.7-2.2-4.1-3.3-5-4.2-9.9-8.6-14.5-13.2s-9.1-9.5-13.2-14.5c-1.1-1.3-2.2-2.7-3.3-4.1-3.1-3.9 5.1 6.8.7.9-2.1-2.8-4.1-5.7-6.1-8.6-7.6-11.4-14-23.4-19.4-36 1 2.4 2 4.8 3 7.2-7.1-17.1-12-34.9-14.5-53.2.4 2.7.7 5.3 1.1 8-2.6-19.4-2.6-38.9 0-58.3-.4 2.7-.7 5.3-1.1 8 2.6-18.3 7.4-36.2 14.5-53.2-1 2.4-2 4.8-3 7.2 5.3-12.6 11.8-24.7 19.4-36 1.9-2.9 4-5.8 6.1-8.6 4.4-5.9-3.8 4.7-.7.9 1.1-1.4 2.2-2.7 3.3-4.1 4.2-5 8.6-9.9 13.2-14.5s9.5-9.1 14.5-13.2c1.3-1.1 2.7-2.2 4.1-3.3 3.9-3.1-6.8 5.1-.9.7 2.8-2.1 5.7-4.1 8.6-6.1 11.4-7.6 23.4-14 36-19.4-2.4 1-4.8 2-7.2 3 17.1-7.1 34.9-12 53.2-14.5-2.7.4-5.3.7-8 1.1 19.4-2.6 38.9-2.6 58.3 0-2.7-.4-5.3-.7-8-1.1 18.3 2.6 36.2 7.4 53.2 14.5-2.4-1-4.8-2-7.2-3 12.6 5.3 24.7 11.8 36 19.4 2.9 1.9 5.8 4 8.6 6.1 5.9 4.4-4.7-3.8-.9-.7 1.4 1.1 2.7 2.2 4.1 3.3 5 4.2 9.9 8.6 14.5 13.2s9.1 9.5 13.2 14.5c1.1 1.3 2.2 2.7 3.3 4.1 3.1 3.9-5.1-6.8-.7-.9 2.1 2.8 4.1 5.7 6.1 8.6 7.6 11.4 14 23.4 19.4 36-1-2.4-2-4.8-3-7.2 7.1 17.1 12 34.9 14.5 53.2-.4-2.7-.7-5.3-1.1-8 1.3 9.7 2 19.4 2 29.1.1 15.7 13.8 30.7 30 30s30.1-13.2 30-30c-.2-49.1-15-98.8-43.7-138.9-29.6-41.5-70-72.5-117.8-90.1-93.3-34.4-204.6-4.2-267.7 72.6-32.9 40.1-52.5 87.9-56.5 139.7-3.8 49.3 8.7 100.3 34.4 142.6 24.8 40.8 62.1 75.1 105.8 94.7 25 11.2 50.1 18.1 77.3 21.3 25.2 3 50.8 1.2 75.7-3.9 95.7-19.4 174.6-101.2 189.2-198 2-13.2 3.4-26.5 3.4-39.9.1-15.7-13.8-30.7-30-30-16.4.7-30 13.1-30.1 29.9z">
                                                </path>
                                            </g>
                                        </g>
                                        <g id="XMLID_2_">
                                            <g id="XMLID_17_">
                                                <path id="XMLID_25_"
                                                    d="m828.7 931.7c-21.3 0-42.6 0-63.9 0-50.8 0-101.7 0-152.5 0-61.3 0-122.6 0-183.9 0-52.8 0-105.5 0-158.3 0-24.8 0-49.5.1-74.3 0-2.5 0-5-.2-7.5-.5 2.7.4 5.3.7 8 1.1-4-.6-7.8-1.7-11.5-3.2l7.2 3c-2.8-1.2-5.5-2.6-8.1-4.3s-3.5-4 1.9 1.6c-1-1.1-2.3-2-3.3-3-.3-.3-3.2-3.2-3-3.3 0 0 5.2 7.3 1.6 1.9-1.7-2.5-3.1-5.2-4.3-8.1 1 2.4 2 4.8 3 7.2-1.5-3.7-2.5-7.6-3.2-11.5.4 2.7.7 5.3 1.1 8-.7-5.6-.5-11.4-.5-17 0-9.7 0-19.5 0-29.2 0-19.4 0-38.8 0-58.2 0-11.5.5-23 2-34.4-.4 2.7-.7 5.3-1.1 8 2.8-20.5 8.2-40.6 16.3-59.7-1 2.4-2 4.8-3 7.2 4.5-10.5 9.7-20.7 15.7-30.5 3-4.9 6.1-9.6 9.5-14.2.8-1.1 1.5-2.1 2.3-3.2.4-.5.8-1 1.2-1.6 1.7-2.3-2.8 4-2.7 3.5.4-2.1 4.4-5.5 5.8-7.1 7.2-8.5 15-16.4 23.4-23.8 2.1-1.9 4.3-3.7 6.5-5.5 1-.8 2-1.6 3.1-2.5 3.4-2.8-6.2 4.6-1.4 1.1 4.6-3.4 9.2-6.7 14-9.7 10.9-7 22.5-13.1 34.4-18.2-2.4 1-4.8 2-7.2 3 19.1-8 39.1-13.5 59.7-16.3-2.7.4-5.3.7-8 1.1 16.4-2.1 32.8-2 49.3-2h67.1 156.6c18.6 0 37.1-.4 55.6 2-2.7-.4-5.3-.7-8-1.1 20.5 2.8 40.6 8.2 59.7 16.3-2.4-1-4.8-2-7.2-3 10.5 4.5 20.7 9.7 30.5 15.7 4.9 3 9.6 6.1 14.2 9.5 1.1.8 2.1 1.5 3.2 2.3.5.4 1 .8 1.6 1.2 2.3 1.7-4-2.8-3.5-2.7 2.1.4 5.5 4.4 7.1 5.8 8.5 7.2 16.4 15 23.8 23.4 1.9 2.1 3.7 4.3 5.5 6.5.8 1 1.6 2 2.5 3.1 2.8 3.4-4.6-6.2-1.1-1.4 3.4 4.6 6.7 9.2 9.7 14 7 10.9 13.1 22.5 18.2 34.4-1-2.4-2-4.8-3-7.2 8 19.1 13.5 39.1 16.3 59.7-.4-2.7-.7-5.3-1.1-8 2.3 18 2 36.1 2 54.2v64.2c0 6.7.4 13.6-.5 20.3.4-2.7.7-5.3 1.1-8-.6 4-1.7 7.8-3.2 11.5 1-2.4 2-4.8 3-7.2-1.2 2.8-2.6 5.5-4.3 8.1s-4 3.5 1.6-1.9c-1.1 1-2 2.3-3 3.3-.3.3-3.2 3.2-3.3 3 0 0 7.3-5.2 1.9-1.6-2.5 1.7-5.2 3.1-8.1 4.3l7.2-3c-3.7 1.5-7.6 2.5-11.5 3.2 2.7-.4 5.3-.7 8-1.1-2.3.3-4.5.4-6.9.5-15.7.2-30.7 13.6-30 30 .7 16.1 13.2 30.2 30 30 36.1-.5 70.5-26.6 76.4-63.2 2.2-13.6 1.6-27.4 1.6-41.1 0-18.1 0-36.3 0-54.4 0-12.7.3-25.5-.7-38.2-4.3-57.8-26.9-111.9-65.1-155.6-35.8-41-86-70.6-139.3-81.8-27.4-5.8-54.6-6.1-82.3-6.1-32.8 0-65.6 0-98.5 0-34.9 0-69.7 0-104.6 0-21.2 0-42.8-.9-63.9 1.4-30.3 3.4-58.6 11.1-86.3 23.9-24.5 11.3-47.2 27.2-66.9 45.6-39.8 37.2-68.2 88.3-77.6 142-6.1 35.1-4.5 70.7-4.5 106.2v41.5c0 28.9 15.4 58.1 42.1 71 12.4 6 25.3 8.8 39.1 8.8h15.1 61.1 92 109 113.2 104.7 82.1 47 6.2c15.7 0 30.7-13.8 30-30-.6-16.3-13-30-29.9-30z">
                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </svg>

                                <p class="mukta-medium text-gray-600">Logged in as <span class="text-green-600">
                                        {{ auth()->user()->name }} ({{ auth()->user()->email }}) </span></p>
                            </div>
                        </div>
                    @endif
                    @php
                         $default_name = $default_address = $default_city = $default_state = $default_country = $default_zipcode = $default_phone = $address_id = '';
                    @endphp
                    <div class="max-w-7xl mx-auto">
                        <form id="checkoutForm" action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            <div id="delivery-section" class="bg-white shadow-md border rounded-lg p-2 lg:p-4 my-3">
                                <h1 class="text-xl mb-4">Shipping Address</h1>
                                <div class="flex flex-col w lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4 mb-5">
                                    @if (!empty($addresses[0]))
                                        @foreach ($addresses as $key => $address)
                                            <div class="addressSelect cursor-pointer p-4 rounded-lg flex-1 mb-0 border @if ((old('address_id') == $address->id) || $address->set_default == 1) active border-green-300 @endif"
                                                id="address-{{ $address->id }}" data-id="{{ $address->id }}"
                                                data-name="{{ $address->name }}" data-address="{{ $address->address }}"
                                                data-city="{{ $address->city }}" data-state="{{ $address->state_name }}"  data-country="{{ $address->country_name }}"
                                                data-zipcode="{{ $address->postal_code }}" data-phone="{{ $address->phone }}">

                                                <p class="font-bold">{{ $address->name }}</p>
                                                <p>{!! nl2br($address->address) !!} <br> {{ $address->city }}, {{ $address->state_name }} <br>
                                                    {{ $address->country_name }} - {{ $address->postal_code }}</p>
                                                <p class="mt-2">{{ $address->phone }}</p>

                                            </div>
                                           
                                            @php
                                                if($address->set_default == 1){
                                                    $address_id = $address->id;
                                                    $default_name = $address->name;
                                                    $default_address = $address->address;
                                                    $default_city = $address->city;
                                                    $default_state = $address->state_name;
                                                    $default_country = $address->country_name;
                                                    $default_zipcode = $address->postal_code;
                                                    $default_phone = $address->phone;
                                                }
                                            @endphp
                                        @endforeach
                                    @endif
                                   
                                </div>
                                
                                <div id="address-field" class="p-4 bg-gray-100 mt-3">
                                    <input type="hidden" id="address_id" name="address_id" value="{{ $address_id }}">
                                    <div class="w-full mb-4">
                                        <label class="block mb-1 font-medium text-gray-800 capitalize">{{trans('messages.full_name')}} *</label>
                                        <input type="text" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" id="billing_name" name="billing_name" placeholder="Enter your full name"  value="{{ old('billing_name', $default_name) }}">
                                        @if ($errors->has('billing_name'))
                                            <div class="text-red-600 mt-2">{{ $errors->first('billing_name') }}</div>
                                        @endif
                                    </div>

                                    <div class="w-full mb-4">
                                        <label class="block mb-1 font-medium text-gray-800 capitalize">Address
                                            *</label>
                                        <textarea placeholder="{{trans('messages.address')}} *" id="billing_address" name="billing_address" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" rows="4">{{ old('billing_address',$default_address) }}</textarea>
                                        @if ($errors->has('billing_address'))
                                            <div class="text-red-600 mt-2">{{ $errors->first('billing_address') }}</div>
                                        @endif
                                    </div>

                                    <div class="grid gap-4 grid-cols-2 md-grid-cols-2 md-grid-cols-2 xl-grid-cols23 xxl-grid-cols-2">
                                        <div class="w-full mb-4">
                                            <label class="block mb-1 font-medium text-gray-800 capitalize">Town / City
                                                *</label>
                                            <input type="text"  id="billing_city" name="billing_city" placeholder="{{trans('messages.town_city')}} *" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('billing_city', $default_city) }}">
                                            @if ($errors->has('billing_city'))
                                                <div class="text-red-600 mt-2">{{ $errors->first('billing_city') }}</div>
                                            @endif
                                        </div>
                                        <div class="w-full mb-4">
                                            <label class="block mb-1 font-medium text-gray-800 capitalize">State *</label>
                                            <input type="text"  id="billing_state" name="billing_state" placeholder="Enter state *" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('billing_state',$default_state) }}">
                                            @if ($errors->has('billing_state'))
                                                <div class="text-red-600 mt-2">{{ $errors->first('billing_state') }}</div>
                                            @endif
                                        </div>

                                        <div class="w-full mb-4 ">
                                            <label class="block mb-1 font-medium text-gray-800 capitalize">Country / Region
                                                *</label>
                                            <input type="text"  id="billing_country" name="billing_country" placeholder="Enter coutry *" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('billing_country','United Arab Emirates') }}">
                                            @if ($errors->has('billing_country'))
                                                <div class="text-red-600 mt-2">{{ $errors->first('billing_country') }}</div>
                                            @endif
                                        </div>
                                        
                                    </div>
                                    <div class="grid gap-4 grid-cols-2 md-grid-cols-2 md-grid-cols-2 xl-grid-cols23 xxl-grid-cols-2"">
                                        
                                        <div class="w-full mb-4 ">
                                            <label class="block mb-1 font-medium text-gray-800 capitalize">ZIP Code</label>
                                            <input type="text" placeholder="{{trans('messages.zip')}}"  id="billing_zipcode" name="billing_zipcode" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('billing_zipcode',$default_zipcode) }}">
                                        </div>

                                        <div class="w-full mb-4 ">
                                            <label class="block mb-1 font-medium text-gray-800 capitalize">Phone *</label>
                                            <input type="tel" placeholder="{{trans('messages.phone')}} *"  id="billing_phone" name="billing_phone" maxlength="15" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('billing_phone', $default_phone) }}">
                                            @if ($errors->has('billing_phone'))
                                                <div class="text-red-600 mt-2">{{ $errors->first('billing_phone') }}</div>
                                            @endif
                                        </div>

                                        <div class="w-full mb-4">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        
                            
                            <div id="billing-section" class="bg-white  border rounded-lg p-2 lg:p-4 my-3">
                                <h1 class="text-xl mb-4">Billing Details</h1>

                                <label class="inline-flex items-center  pe-6">
                                    <input type="checkbox" id="checkbox2" name="same_as_billing" class="form-radio h-5 w-5 text-[#41b6e8]" @if(old('same_as_billing') == 'on') checked @endif/>
                                    <span class="ml-2 text-md text-gray-700">Same as shipping address</span>
                                </label>

                                <!-- Default Address Fields -->
                                <div id="address-fields" class="p-4 bg-gray-100 mt-3 shipping-address">
                                    <div class="w-full mb-4">
                                        <label class="block mb-1 font-medium text-gray-800 capitalize">{{trans('messages.full_name')}} *</label>
                                        <input type="text" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" id="shipping_name" name="shipping_name" placeholder="Enter your full name"  value="{{ old('shipping_name',auth()->user()?->name) }}" @if(old('same_as_billing') == 'on') readonly @endif>
                                        @if ($errors->has('shipping_name'))
                                            <div class="text-red-600 mt-2">{{ $errors->first('shipping_name') }}</div>
                                        @endif
                                    </div>

                                    <div class="w-full mb-4">
                                        <label class="block mb-1 font-medium text-gray-800 capitalize">Address
                                            *</label>
                                        <textarea placeholder="{{trans('messages.address')}} *" id="shipping_address" name="shipping_address" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" rows="4" @if(old('same_as_billing') == 'on') readonly @endif>{{ old('shipping_address') }}</textarea>
                                        @if ($errors->has('shipping_address'))
                                            <div class="text-red-600 mt-2">{{ $errors->first('shipping_address') }}</div>
                                        @endif
                                    </div>

                                    <div class="grid gap-4 grid-cols-2 md-grid-cols-2 md-grid-cols-2 xl-grid-cols23 xxl-grid-cols-2"">
                                        <div class="w-full mb-4">
                                            <label class="block mb-1 font-medium text-gray-800 capitalize">Town / City
                                                *</label>
                                            <input type="text"  id="shipping_city" name="shipping_city" placeholder="{{trans('messages.town_city')}} *" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('shipping_city') }}" @if(old('same_as_billing') == 'on') readonly @endif>
                                            @if ($errors->has('shipping_city'))
                                                <div class="text-red-600 mt-2">{{ $errors->first('shipping_city') }}</div>
                                            @endif
                                        </div>
                                        <div class="w-full mb-4">
                                            <label class="block mb-1 font-medium text-gray-800 capitalize">State *</label>
                                            <input type="text"  id="shipping_state" name="shipping_state" placeholder="Enter state *" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('shipping_state') }}" @if(old('same_as_billing') == 'on') readonly @endif>
                                            @if ($errors->has('shipping_state'))
                                                <div class="text-red-600 mt-2">{{ $errors->first('shipping_state') }}</div>
                                            @endif
                                        </div>

                                        <div class="w-full mb-4">
                                            <label class="block mb-1 font-medium text-gray-800 capitalize">Country / Region
                                                *</label>
                                            <input type="text"  id="shipping_country" name="shipping_country" placeholder="Enter coutry *" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('shipping_country','United Arab Emirates') }}" @if(old('same_as_billing') == 'on') readonly @endif>
                                            @if ($errors->has('shipping_country'))
                                                <div class="text-red-600 mt-2">{{ $errors->first('shipping_country') }}</div>
                                            @endif
                                        </div>
                                        
                                    </div>
                                    <div class="grid gap-4 grid-cols-2 md-grid-cols-2 md-grid-cols-2 xl-grid-cols23 xxl-grid-cols-2"">
                                        
                                        <div class="w-full mb-4">
                                            <label class="block mb-1 font-medium text-gray-800 capitalize">ZIP Code</label>
                                            <input type="text" placeholder="{{trans('messages.zip')}}"  id="shipping_zipcode" name="shipping_zipcode" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('shipping_zipcode') }}" @if(old('same_as_billing') == 'on') readonly @endif>
                                        </div>

                                        <div class="w-full mb-4 ">
                                            <label class="block mb-1 font-medium text-gray-800 capitalize">Phone *</label>
                                            <input type="tel" placeholder="{{trans('messages.phone')}} *"  id="shipping_phone" name="shipping_phone" maxlength="15" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('shipping_phone', auth()->user()?->phone) }}"  @if(old('same_as_billing') == 'on') readonly @endif>
                                            @if ($errors->has('shipping_phone'))
                                                <div class="text-red-600 mt-2">{{ $errors->first('shipping_phone') }}</div>
                                            @endif
                                        </div>

                                        <div class="w-full mb-4 ">
                                            <label class="block mb-1 font-medium text-gray-800 capitalize">Email *</label>
                                            <input type="text"  id="billing_email" name="billing_email" placeholder="{{trans('messages.email')}} *" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('billing_email',auth()->user()?->email) }}">
                                            @if ($errors->has('billing_email'))
                                                <div class="text-red-600 mt-2">{{ $errors->first('billing_email') }}</div>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <div class="mb-5 mt-5">
                                    <label for="delivery-instructions"
                                        class="block mb-2 text-sm font-medium text-gray-700">Delivery instructions</label>
                                    <textarea placeholder="{{trans('messages.order_note')}}" name="order_note" class="block w-full p-2 border border-gray-300 rounded-lg" rows="3">{{ old('order_note') }}</textarea>
                                    
                                </div>
                            </div>
                            <div class="space-y-5 mt-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                                <h3 class="text-xl font-medium text-gray-900 ">Payment Method</h3>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 ps-4">
                                        <div class="flex items-start">
                                            <div class="flex h-5 items-center">
                                                <input  name="payment_method" id="payment_method" value="cod" aria-describedby="pay-on-delivery-text" type="radio" class="h-4 w-4 border-gray-300 bg-white text-primary-600 focus:ring-2 focus:ring-primary-600"
                                                    checked />
                                            </div>
                                            <div class="ms-4 text-sm">
                                                <label for="pay-on-delivery"
                                                    class="font-medium leading-none text-gray-900">
                                                    Cash On Delivery </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
                <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">
                    <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm   sm:p-6">
                        <h3 class="text-xl font-medium text-gray-900 ">Order Summary</h3>
                        <div class="space-y-6">
                            @if (!empty($response['products']))
                                @foreach ($response['products'] as $key => $prod)
                                    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm   md:p-3">
                                        <div
                                            class="space-y-4 flex-wrap md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                            <a href="{{ route('products.show', ['slug' => $prod['product']['slug']]) }}"
                                                class="shrink-0 md:order-1">
                                                <img class=" h-10 w-10 dark:block rounded-md"
                                                    src="{{ $prod['product']['image'] }}"
                                                    alt=" {{ $prod['product']['name'] }}" />
                                            </a>
                                            <div class="flex flex-wrap items-center justify-between md:order-3 md:justify-end ">
                                                <div class="text-end md:order-4 md:w-32">
                                                    <p class="text-base text-lg font-semibold text-gray-900 text-black">
                                                        {{ env('DEFAULT_CURRENCY') }} {{ $prod['main_price'] }}
                                                        @if ($prod['main_price'] != $prod['stroked_price'])
                                                            <br>
                                                            <span class="text-gray-500 line-through text-xs">
                                                                &nbsp;{{ env('DEFAULT_CURRENCY') }}
                                                                {{ $prod['stroked_price'] }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                                <a href="#"
                                                    class="text-base font-medium text-gray-900 hover:underline text-black">
                                                    {{ $prod['product']['name'] }} x {{ $prod['quantity'] }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <x-coupon-apply :response="$response" />
                    <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm   sm:p-6">

                        <x-order-summary :response="$response" />

                        <button id="submitCheckout" class="flex w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 bg-[#41b6e8] py-4">Place
                                Order</button>

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
        const phoneInput = document.getElementById('billing_phone');

        phoneInput.addEventListener('input', () => {
            phoneInput.value = phoneInput.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
        });

        const phoneInputShip = document.getElementById('shipping_phone');

        phoneInputShip.addEventListener('input', () => {
            phoneInputShip.value = phoneInputShip.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
        });


        $('#checkbox2').on('change', function () {
            if ($(this).is(':checked')) {
                $('#shipping_name').val($('#billing_name').val());
                $('#shipping_address').val($('#billing_address').val());
                $('#shipping_city').val($('#billing_city').val());
                $('#shipping_state').val($('#billing_state').val());
                $('#shipping_country').val($('#billing_country').val());
                $('#shipping_zipcode').val($('#billing_zipcode').val());
                $('#shipping_phone').val($('#billing_phone').val());
                
                $('.shipping-address input').not('#billing_email').prop('readonly', true);
                $('.shipping-address textarea').prop('readonly', true);
            } else {
                $('.shipping-address textarea').val('').prop('readonly', false);
                $('.shipping-address input').not('#billing_email').val('').prop('readonly', false);
            }
        });

        $(document).on('click', '.addressSelect', function () {
            if ($(this).hasClass('active')) {
                // If already active, unselect and clear all fields
                $(this).removeClass('border-green-300 active');

                $('#address_id').val('');
                $('#billing_address').val('');
                $('#billing_name').val('');
                $('#billing_phone').val('');
                $('#billing_city').val('');
                $('#billing_state').val('');
                $('#billing_country').val('');
                $('#billing_zipcode').val('');

                if ($('#checkbox2').is(':checked')) {
                    $('#shipping_name').val('');
                    $('#shipping_address').val('');
                    $('#shipping_city').val('');
                    $('#shipping_state').val('');
                    $('#shipping_country').val('');
                    $('#shipping_zipcode').val('');
                    $('#shipping_phone').val('');
                }
            } else {
                // Make this one active and fill fields
                $('.addressSelect').removeClass('border-green-300 active');
                $(this).addClass('border-green-300 active');

                let id = $(this).data('id');
                let name = $(this).data('name');
                let address = $(this).data('address');
                let phone = $(this).data('phone');
                let city = $(this).data('city');
                let state = $(this).data('state');
                let country = $(this).data('country');
                let zipcode = $(this).data('zipcode');

                $('#address_id').val(id);
                $('#billing_address').val(address);
                $('#billing_name').val(name);
                $('#billing_phone').val(phone);
                $('#billing_city').val(city);
                $('#billing_state').val(state);
                $('#billing_country').val(country);
                $('#billing_zipcode').val(zipcode);

                if ($('#checkbox2').is(':checked')) {
                    $('#shipping_name').val(name);
                    $('#shipping_address').val(address);
                    $('#shipping_city').val(city);
                    $('#shipping_state').val(state);
                    $('#shipping_country').val(country);
                    $('#shipping_zipcode').val(zipcode);
                    $('#shipping_phone').val(phone);
                }
            }
        });

        $('#submitCheckout').on('click', function () {
            $('#checkoutForm').submit();
        });

        $('#checkoutForm :input').on('input', function () {
            let formData = {};
            $('#checkoutForm :input').each(function () {
                let name = $(this).attr('name');
                let value = $(this).val();
                if (name) {
                    formData[name] = value;
                }
            });
            sessionStorage.setItem('checkout_form_data', JSON.stringify(formData));
        });
        @if ($errors->any())
            // Restore form data when page loads
            let savedData = sessionStorage.getItem('checkout_form_data');
            if (savedData) {
                savedData = JSON.parse(savedData);
                $('#checkoutForm :input').each(function () {
                    let name = $(this).attr('name');
                    if (name && savedData[name]) {
                        $(this).val(savedData[name]);
                    }
                });
            }
        @endif
    });
 </script>
@endsection