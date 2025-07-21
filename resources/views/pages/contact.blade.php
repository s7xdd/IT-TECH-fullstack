@extends('layouts.app')

@section('title', 'Contact Us - ' . env('APP_NAME'))

@section('content')

    <section class="py-16 px-6 sm:py-20 sm:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24 relative z-10 mt-[84px]">
        <div class="lg:w-5/6 mx-auto">
            <div class="flex flex-col md:flex-row gap-5 justify-center items-start">

                <div class="w-full h-full md:w-1/3 p-4 md:py-8 mb-8 contact-box">

                    @if (session('success'))
                        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded">
                            {{ session('success') }}
                        </div>
                    @endif


                    <div class="mb-8">
                        <span class="flex w-fit items-center bg-gray-200 rounded-full py-2 px-3">Let's work together</span>
                        <h2 class="text-3xl my-2 text-[--primary]"> {{ $page->getTranslation('title', $lang) }}</h2>
                        <p class="text-gray-600"> {{ $page->getTranslation('sub_title', $lang) }}</p>
                    </div>


                    <div class="flex flex-col items-start space-y-6">
                        <div class="flex flex-col">
                            <span class="text-gray-400">Call us directly?</span>
                            <a href="tel:+12345678910"
                                class="text-gray-600 text-xl hover:text-[--dark] hover:underline underline-offset-1">+1234
                                567
                                8910</a>
                        </div>
                        <div class="h-0.5 bg-gray-200 w-full"></div>
                        <div class="flex flex-col">
                            <span class="text-gray-400">Need live support?</span>

                            <a href="mailto:{{ $page->getTranslation('heading4', $lang) }}"
                                class="text-gray-600 hover:text-[--dark] text-xl hover:underline underline-offset-1">
                                {{ $page->getTranslation('heading4', $lang) }}
                            </a>

                        </div>
                        <div class="h-0.5 bg-gray-200 w-full"></div>
                        <div class="flex flex-col">
                            <span class="text-gray-400">Join growing team?</span>
                            <a href="mailto:join@domain.com"
                                class="text-gray-600 hover:text-[--dark] text-xl hover:underline underline-offset-1">
                                {{ $page->getTranslation('heading5', $lang) }}</a>
                        </div>
                        <div class="h-0.5 bg-gray-200 w-full"></div>
                        <div class="flex flex-col">
                            <span class="text-gray-400">Visit headquarters?</span>
                            <a href="{{ $page->getTranslation('content1', $lang) }}"
                                class="text-gray-600 hover:text-[--dark] text-xl hover:underline underline-offset-1">View on
                                google map</a>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-2/3 p-8 md:p-16 bg-[--primary] text-white">

                    <h1 class="responsive-heading mb-4 text-white">{{ $page->getTranslation('heading2', $lang) }}</h1>

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="relative">
                            <label for="name" class="block text-white mb-1">Enter Your Name*</label>
                            <input type="text" id="name" name="name" placeholder="What's your good name?"
                                required value="{{ old('name') }}" minlength="3"
                                class="w-full border-b border-gray-100 bg-transparent py-4 px-0 focus:outline-none focus:border-white text-white mb-2 pr-12">
                            @error('name')
                                <div class="text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="relative">
                            <label for="email" class="block text-white mb-1">Email Address*</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email address"
                                required
                                class="w-full border-b border-gray-300 bg-transparent py-4 px-0 focus:outline-none focus:border-[--primary] text-white mb-2 pr-12"
                                value="{{ old('email') }}">
                            @error('email')
                                <div class="text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="relative">
                            <label for="message" class="block text-white mb-1">Your Message</label>
                            <textarea id="message" name="message" rows="5" placeholder="Describe about your project" required minlength="10"
                                class="w-full border-b border-gray-300 bg-transparent py-4 px-0 focus:outline-none focus:border-[--primary] text-white mb-2 pr-12">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex items-center justify-start gap-6">
                            <button
                                class="bg-white whitespace-nowrap text-base sm:text-lg text-[--dark] px-6 py-3 sm:px-6 sm:py-4 rounded-full hover:bg-gray-100 w-full sm:w-auto">
                                Get Started
                            </button>
                            <p class="text-base text-white">
                                We will never collect information about you without your explicit consent.
                            </p>
                        </div>

                    </form>

                    @if ($errors->any())
                        <div class="mt-4 text-white">
                            {{ $errors->first() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

@endsection
