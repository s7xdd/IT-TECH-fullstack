@extends('layouts.app')
@section('title', 'Faq - '.env('APP_NAME'))
@section('content')
<div class="container mx-auto py-12 px-6 lg:px-12">
   <h1 class="text-3xl mb-8 text-center">Change Password</h1>

   <div class="flex justify-center items-center">
            <div class="bg-white rounded-lg shadow-md p-8 w-full max-w-4xl">
                
                <form class="contact-page__form" action="{{ route('account.changePassword') }}" method="POST"  enctype="multipart/form-data">
                    @csrf
                    <div class="input-item flex space-x-2.5 mb-5 mt-3">

                        <div class="w-full h-full">
                            <div x-data="{ show: false }" class="mb-4">
                                <label for="password" class="block text-gray-700 font-medium mb-1">{{ trans('messages.current_password') }}</label>
                            
                                <div class="relative">
                                    <!-- Password Input -->
                                    <input :type="show ? 'text' : 'password'" placeholder="Enter password.." id="current_password" name="current_password" autocomplete="new-password" class="w-full p-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm" value="{{old('current_password')}}">
                            
                                    <!-- Toggle Icon Inside Input on the Right -->
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-5 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                                        <!-- Show Icon -->
                                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                            
                                        <!-- Hide Icon -->
                                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.964 9.964 0 012.184-3.293m1.43-1.43A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.969 9.969 0 01-4.138 5.132M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            
                                @error('current_password')
                                    <span class="text-red-600">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="input-item flex space-x-2.5 mb-5 mt-5">
                        <div class="w-full h-full">
                            
                            <div x-data="{ show: false }" class="mb-4">
                                <label for="password" class="block text-gray-700 font-medium mb-1">{{ trans('messages.new_password') }}</label>
                            
                                <div class="relative">
                                    <!-- Password Input -->
                                    <input :type="show ? 'text' : 'password'" placeholder="Enter password.." id="new_password" name="new_password" autocomplete="new-password" class="w-full p-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm" value="{{old('new_password')}}">
                            
                                    <!-- Toggle Icon Inside Input on the Right -->
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-5 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                                        <!-- Show Icon -->
                                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                            
                                        <!-- Hide Icon -->
                                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.964 9.964 0 012.184-3.293m1.43-1.43A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.969 9.969 0 01-4.138 5.132M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            
                                @error('new_password')
                                    <span class="text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="input-item flex space-x-2.5 mb-5 mt-5">

                        <div class="w-full h-full">
                        
                            <div x-data="{ show: false }" class="mb-4">
                                <label for="password" class="block text-gray-700 font-medium mb-1">{{ trans('messages.confirm_new_password') }}</label>
                            
                                <div class="relative">
                                    <!-- Password Input -->
                                    <input :type="show ? 'text' : 'password'" placeholder="Enter password.." id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password" class="w-full p-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm" value="{{old('new_password_confirmation')}}">
                            
                                    <!-- Toggle Icon Inside Input on the Right -->
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-5 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                                        <!-- Show Icon -->
                                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                            
                                        <!-- Hide Icon -->
                                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.964 9.964 0 012.184-3.293m1.43-1.43A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.969 9.969 0 01-4.138 5.132M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            
                                @error('new_password_confirmation')
                                    <span class="text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 flex justify-between items-center">
                        <div class="flex space-x-2">
                            <a class="flex items-center justify-center px-4 py-2.5 text-sm font-bold text-center text-[#41b6e8] border border-[#41b6e8] transition-all duration-200 hover:text-[#41b6e8]  hover:bg-[#d7f0db] " href="{{ route('update-password') }}">Cancel</a>
                            <button class="flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white transition-all border  border-[#41b6e8] duration-200 bg-[#41b6e8]  hover:text-[#41b6e8]  hover:bg-[#d7f0db]" type="submit">Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

</div>
@endsection

@section('script')
    @if(session()->has('message'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                toastr.{{ session('alert-type', 'info') }}("{{ session('message') }}");
            });
        </script>
    @endif

@endsection