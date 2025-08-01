@extends('layouts.app')

@section('title', $tutorial->name . ' - ' . env('APP_NAME'))

@section('content')
    <main>
        <section class="py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24 relative z-10 mt-[84px]">
            <div class="lg:w-5/6 mx-auto">
                <div class="flex mb-10">
                    <div class="max-w-xl">
                        <span class="bg-gray-200 rounded-full py-2 px-3">
                            {{ \Carbon\Carbon::parse($tutorial['tutorial_date'])->format('j F Y') }}</span>
                        <h1 class="responsive-heading my-4">
                            @php
                                $text = $tutorial->name;
                                $words = explode(' ', $text);

                                $formattedText = preg_replace(
                                    '/\[(.*?)\]/',
                                    '<span class="text-sky-400">$1</span>',
                                    implode(' ', array_slice($words, 0, 2)) .
                                        '<br>' .
                                        implode(' ', array_slice($words, 2)),
                                );

                            @endphp
                            {!! $formattedText !!}
                        </h1>
                        <p class="text-gray-500 text-base font-light">
                            Published on {{ \Carbon\Carbon::parse($tutorial['tutorial_date'])->format('j F Y') }}
                        </p>
                    </div>
                    <div>

                    </div>
                </div>
                <div>
                    <img src="{{ uploaded_asset($tutorial->image) }}" alt="{{ $tutorial->name }}"
                        class="w-full h-full object-cover h-[400px] lg:h-[560px]">

                    <div class="space-y-8 text-2xl leading-relaxed text-gray-700 mt-12 lg:mt-16 xl:mt-20 max-w-full">
                        {!! $tutorial->description !!}
                    </div>

                </div>
            </div>
        </section>

        <section class="bg-[#f4f9ff] py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24">
            <div class="lg:w-5/6 mx-auto">
                <div class="max-w-lg">
                    <h2 class="responsive-heading mb-4">{{ $page->getTranslation('title', $lang) }}</h2>
                    <p class="font-light font-[--aspekta] text-gray-600 text-xl mb-10">
                        {{ $page->getTranslation('heading2', $lang) }}
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach ($recentTutorials as $post)
                        <div
                            class="bg-white p-5 hover:shadow-lg hover:-translate-y-3 duration-300 group border border-[--dark]">
                            <div class="bg-gray-200 h-[250px]">
                                <img src="{{ uploaded_asset($post->image) }}" class="h-[250px] w-full object-cover"
                                    alt="news" />

                            </div>
                            <div class="mt-6 mb-3">
                                <span class="text-gray-500">
                                    {{ $post->name }}
                                </span>
                                <p class="mb-5 text-xl hover:underline underline-offset-2">
                                    <a href="news-detail.html">
                                        {{ Str::limit(strip_tags($post->description), 80, '...') }}
                                    </a>
                                </p>
                                <a href="{{ route('blog.details', ['slug' => $post->slug]) }}"
                                    class="mt-auto border border-black rounded-full px-6 py-3 text-[--dark] group-hover:bg-[--primary] group-hover:border-[--primary] group-hover:text-white transition w-max group-hover:opacity-100 hover:shadow-lg">
                                    Learn More
                                </a>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </section>


    </main>

@endsection


@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@endsection
