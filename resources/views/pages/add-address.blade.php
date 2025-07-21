@extends('layouts.app')

@section('title', 'Add Address - '.env('APP_NAME'))

@section('content')

    <div class="container mx-auto py-12 px-6 lg:px-12">

        <!-- Section Title -->
        <h2 class="text-3xl mb-8 text-center">
            @if (isset($address) && $address->id)
                <span >Update Address</span>
            @else
                <span >Add Address</span>
            @endif
            
        </h2>

        <div class="flex justify-center items-center">
            <div class="bg-white rounded-lg shadow-md p-0 xl:p-8 w-full max-w-4xl">
                <form id="addressForm" action="{{ route('save-address') }}" method="POST">
                    @csrf
                    <div class="p-4 bg-gray-100  mt-3">
                        <div class="mb-4 md:flex  flex-col">
                            <div class="w-full mb-2">
                                <input type="hidden" name="address_id" value="{{ $address->id ?? '' }}">
                                <label class="block mb-1 font-medium text-gray-800 capitalize">Full Name *</label>
                                <input type="text" id="name" name="name" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" placeholder="Enter your name" value="{{ old('name', $address->name ?? '') }}">
                                @error('name')
                                    <div class="text-red-500">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
        
                        <div class="w-full mb-4 ">
                            <label class="block mb-1 font-medium text-gray-800 capitalize">Address *</label>
                            <input type="text"  id="address" name="address" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('address', $address->address ?? '') }}"
                                placeholder="House number and street name" >
                            @error('address')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
        
                        <div class="w-full mb-4 ">
                            <label class="block mb-1 font-medium text-gray-800 capitalize">Town / City *</label>
                            <input type="text"  id="city" name="city" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('city', $address->city ?? '') }}"
                                placeholder="Enter your town/city" >
                            @error('city')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
        
                        <div class="w-full mb-4 ">
                            <label class="block mb-1 font-medium text-gray-800 capitalize">State *</label>
                            <input type="text"  id="state" name="state" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('state', $address->state_name ?? '') }}"
                                placeholder="Enter your state" >
                            @error('state')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
        
                        <div class="w-full mb-4">
                            <label class="block mb-1 font-medium text-gray-800 capitalize">Country *</label>
                            <input type="text"  id="country" name="country" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" value="{{ old('country', $address->country_name ?? 'United Arab Emirates') }}"
                                placeholder="Enter your country" >
                            @error('country')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        
                        </div>
                        
                    
                        <div class="w-full mb-4 ">
                            <label class="block mb-1 font-medium text-gray-800 capitalize">ZIP Code</label>
                            <input type="text" id="zipcode" name="zipcode" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" placeholder="Enter your ZIP code"  value="{{ old('zipcode', $address->postal_code ?? '') }}">
                        </div>

                        <div class="w-full mb-4 ">
                            <label class="block mb-1 font-medium text-gray-800 capitalize">Phone Number *</label>
                            <input type="text" id="phone" name="phone" class="w-full bg-gray-50 py-4 ps-6 rounded-lg border border-gray-300" placeholder="Enter your phone"  value="{{ old('phone', $address->phone ?? '') }}">
                            @error('phone')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <div class="flex items-center gap-4 mb-5">
                                <div class="flex items-center">
                                    <input id="address_type" type="radio" name="address_type" value="work" @if(old('address_type',$address->type ?? '') == 'work') checked @endif
                                        class="w-5 h-5 text-[#41b6e8] border-gray-300 focus:ring-blue-500">
                                    <label for="work" class="ml-2 text-md  text-gray-700">Work</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="address_type" type="radio" name="address_type" value="home" @if(old('address_type',$address->type ?? '') == 'home') checked @endif
                                        class="w-5 h-5 text-[#41b6e8] border-gray-300 focus:ring-blue-500">
                                    <label for="home" class="ml-2 text-md  text-gray-700">Home</label>
                                </div>
        
                                <div class="flex items-center">
                                    <input id="address_type" type="radio" name="address_type" value="other" @if(old('address_type',$address->type ?? 'other') == 'other') checked @endif
                                        class="w-5 h-5 text-[#41b6e8] border-gray-300 focus:ring-blue-500">
                                    <label for="other" class="ml-2 text-md  text-gray-700">Other</label>
                                </div>
                            </div>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" id="default" name="default" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" value="1" @if(old('default',$address->set_default ?? '') == 1) checked @endif>
                                <span class="text-gray-700">Set as Default Address</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4 p-5 px-xxl-0">
                        <a class="flex items-center justify-center px-4 py-2.5 text-sm font-bold text-center text-[#41b6e8] border border-[#41b6e8] transition-all duration-200 hover:text-[#41b6e8]  hover:bg-[#d7f0db] " href="{{ route('my-address') }}">Cancel</a>
                        <button class="flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white transition-all border  border-[#41b6e8] duration-200 bg-[#41b6e8]  hover:text-[#41b6e8]  hover:bg-[#d7f0db]" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        
    });
</script>
@endsection
