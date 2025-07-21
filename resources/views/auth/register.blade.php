@extends('layouts.app')
@section('title', 'Register - '.env('APP_NAME'))
@section('content')

<div class="container mx-auto py-12 px-6 lg:px-12">
    <div class="max-w-2xl m-auto bg-white shadow-2xl rounded-2xl p-4 xl:p-8 transform transition duration-300">
    <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-6">Register</h1>
        
        <form action="{{ url('register') }}" method="POST" class="space-y-6" autocomplete="off">
            @csrf
            <div>
                <label for="name" class="block text-gray-700 font-medium mb-1">Full Name</label>
                <input type="text" id="name"  class="w-full text-xs xl:text-[16px] mt-1 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm" name="name" value="{{ old('name') }}" placeholder="Enter name.." pattern="[A-Za-z ]+" title="Only letters and spaces are allowed.">
                @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" id="email" placeholder="Enter email.." class="w-full text-xs xl:text-[16px] mt-1 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm" autocomplete="off" name="email" value="{{ old('email') }}">
                @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                <input type="tel" name="phone" id="phone" autocomplete="off" class="w-full text-xs xl:text-[16px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary outline-none"  value="{{ old('phone') }}" placeholder="Enter phone..">
                @error('phone') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            
            {{-- <div>
                <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" id="password" name="password" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm" autocomplete="new-password" placeholder="Enter password..">
                @error('password') <span class="text-red-600">{{ $message }}</span> @enderror
            </div> --}}

            <div x-data="{ show: false }" class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
            
                <div class="relative">
                    <!-- Password Input -->
                    <input :type="show ? 'text' : 'password'" id="password" name="password" class="w-full text-xs xl:text-[16px] p-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm" placeholder="Enter password" autocomplete="new-password" >
            
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
            
                @error('password')
                    <span class="text-red-600">{{ $message }}</span>
                @enderror
            </div>
            
            
            <div x-data="{ show: false }" class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-1">Confirm Password</label>
            
                <div class="relative">
                    <!-- Password Input -->
                    <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                        class="w-full text-xs xl:text-[16px] p-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm" placeholder="Enter confirm password.." autocomplete="new-password" >
            
                    <!-- Toggle Icon Inside Input on the Right -->
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-5 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" >
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
            
                @if ($errors->has('password_confirmation'))
                    <p class="text-red-600">{{ $errors->first('password_confirmation') }}</p>
                @elseif ($errors->has('password'))
                    @if (str_contains($errors->first('password'), 'confirmation'))
                        <p class="text-red-600">{{ $errors->first('password') }}</p>
                    @endif
                @endif
            </div>
            
            <button type="submit" class="w-full bg-primary text-white py-4 rounded-full hover:bg-[#3498db] transition-all duration-300 transform hover:scale-105 font-medium text-sm">Register</button>
        </form>
        
        <p class="text-center text-gray-700 mt-6 text-sm">Already have an account? <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Login</a></p>
    </div>
    </div>

@endsection

@section('script')
    <script>
        document.querySelector('input[name="phone"]').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
        });
    </script>
@endsection
