@extends('layouts.app')

@section('title', 'About Us - ' . env('APP_NAME'))

@section('content')
    <section class="py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24 relative z-10 mt-[84px]">
        <div class="flex lg:w-5/6 mx-auto">
            <div class="max-w-xl">
                <span class="bg-gray-200 rounded-full py-2 px-3">About Us</span>
                <h1 class="responsive-heading my-4">
                    @php
                        $text = $page->getTranslation('title', $lang);
                        $formattedText = preg_replace('/\[(.*?)\]/', '<span class="text-sky-400">$1</span>', $text);
                    @endphp
                    {!! $formattedText !!}

                </h1>
            </div>
            <div>

            </div>
        </div>
    </section>

    <section class="py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24 relative z-10 bg-[--primary]">
        <div class="flex flex-col lg:flex-row items-center lg:w-5/6 mx-auto gap-12">
            <div class="w-full lg:w-1/2 px-10">
                <div class="w-full bg-gray-200 h-[400px] mx-auto">
                    <img src="{{ asset($page->image) }}" alt="About Us Image"
                        class="w-full h-full object-cover">
                </div>
            </div>
            <div class="w-full lg:w-1/2">

                <p class="text-3xl text-white leading-tight font-light mb-8">
                    {{ $page->getTranslation('content', $lang) }}
                </p>

                <a href="{{ route('services.index') }}"
                    class="bg-transparent transition-all duration-150 text-base sm:text-lg border border-white text-white px-6 py-3 sm:px-6 sm:py-4 rounded-full hover:bg-white hover:text-blue-800 w-auto sm:w-auto hover:shadow-lg hover:-translate-y-1">
                    Our Services
                </a >

            </div>

        </div>
    </section>

    <section class="pb-16 px-6 sm:pb-20 md:px-6 lg:px-0 md:pb-24 lg:pb-24 xl:pb-24 relative z-10 bg-[--primary]">
        <div class="flex flex-col lg:flex-row items-start justify-center lg:w-5/6 mx-auto gap-2 lg:gap-24">
            <div class="max-w-lg min-w-[300px]">
                <h2 class="responsive-heading mb-4 capitalize text-white"> {{ $page->getTranslation('heading2', $lang) }}
                </h2>
            </div>
            <div class="text-white w-auto">
                <p class="font-light font-[--aspekta] text-white text-base sm:text-lg md:text-xl">
                    {{ $page->getTranslation('content1', $lang) }}
                </p>
            </div>
        </div>
    </section>

    <section class="py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24 relative z-10">
        <div class="lg:w-5/6 mx-auto">

            <div class="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8">
                <div class="col-span-2 flex justify-start">
                    <div class="max-w-lg">
                        <h2 class="responsive-heading mb-4 capitalize">
                            @php
                                $text = $page->getTranslation('heading4', $lang);
                                $formattedText = preg_replace(
                                    '/\[(.*?)\]/',
                                    '<span class="text-sky-400">$1</span>',
                                    $text,
                                );
                            @endphp
                            {!! $formattedText !!}

                        </h2>
                        <p class="font-light font-[--aspekta] text-gray-600 text-base sm:text-lg md:text-xl">
                            {{ $page->getTranslation('content2', $lang) }}
                        </p>
                    </div>
                </div>

                @php
                    $headings = [
                        $page->getTranslation('heading5', $lang),
                        $page->getTranslation('heading6', $lang),
                        $page->getTranslation('heading7', $lang),
                        $page->getTranslation('heading8', $lang),
                    ];
                    $titles = [
                        $page->getTranslation('content3', $lang),
                        $page->getTranslation('content4', $lang),
                        $page->getTranslation('content5', $lang),
                        $page->getTranslation('content6', $lang),
                    ];
                    $icons = [$page->image1, $page->image2, $page->image3, $page->image4];
                @endphp
                @foreach ($headings as $i => $heading)
                    <div
                        class="bg-gray-100 h-full flex flex-col justify-between items-start p-8 min-h-[270px] col-span-2 sm:col-span-1">
                        <img src="{{ uploaded_asset($icons[$i]) }}" alt="icon {{ $i + 1 }}" class="w-10 h-10 mb-4">
                        <div>
                            <h4 class="text-xl mb-2">{{ $headings[$i] }}</h4>
                            <p>{{ $titles[$i] }}</p>
                        </div>
                    </div>
                @endforeach


            </div>

        </div>
    </section>

    <x-home.careers :page="$home_data" :lang="$lang" />

@endsection
