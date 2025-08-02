@extends('layouts.app')

@section('title', $service->getTranslation('name', $lang) . ' - ' . env('APP_NAME'))

@section('content')

    @php
        $serviceCustomFields = json_decode($service->custom_fields);
    @endphp


    <section class="py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24 relative z-10 mt-[84px]">
        <div class="flex lg:w-5/6 mx-auto">
            <div class="max-w-xl">
                <span class="bg-gray-200 rounded-full py-2 px-3">Our Services</span>
                <h1 class="responsive-heading my-4">

                    @php
                        $text = $service->getTranslation('name', $lang);
                        $words = explode(' ', $text);

                        $formattedText = preg_replace(
                            '/\[(.*?)\]/',
                            '<span class="text-sky-400">$1</span>',
                            implode(' ', array_slice($words, 0, 2)) . '<br>' . implode(' ', array_slice($words, 2)),
                        );

                    @endphp
                    {!! $formattedText !!}

                </h1>
            </div>
            <div>

            </div>
        </div>
    </section>

    <section class="bg-[--primary] py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24">
        <div class="lg:w-5/6 mx-auto ">
            <div class="flex flex-col lg:flex-col xl:flex-row items-start justify-center gap-2 lg:gap-5 xl:gap-24 mb-10">
                <div class="max-w-lg min-w-[100%] xl:min-w-[300px]">
                    <h2 class="responsive-heading mb-4 capitalize text-white">
                        {{ $service->getTranslation('short_description', $lang) }}
                    </h2>
                </div>
                <div class="text-white w-auto flex-1">
                    <p class="font-light font-[--aspekta] text-white text-xl">
                        {!! $service->getTranslation('description', $lang) !!}
                    </p>
                </div>
            </div>

            <div class="flex-1 bg-white h-[600px]">
                <img src="{{ uploaded_asset($service->image) }}" class="w-full h-full object-cover" alt="">
            </div>

        </div>
    </section>

    <section class=" py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24 ">
        <div class="w-full mx-auto">
            <div class="grid grid-cols-1 gap-3">
                <section class=" py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-0">
                    <div class="lg:w-5/6 mx-auto">

                        <div class="max-w-lg mb-8">
                            <h2 class="responsive-heading mb-4">{{ $page->getTranslation('title1', $lang) }}
                                {{ $service->getTranslation('name', $lang) }} Services?
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 gap-3">

                            @for ($i = 1; $i <= 6; $i++)
                                @if (
                                    !empty($service->getTranslation('feature_title_' . $i, $lang)) ||
                                        !empty($service->getTranslation('feature_content_' . $i, $lang)))
                                    <div
                                        class="grid grid-cols-12 gap-4 sm:gap-6 md:gap-6 bg-gray-100 p-4 sm:p-6 md:p-10 hover:bg-[#5DB2E3]/20 duration-150">
                                        <div
                                            class="col-span-12 md:col-span-1 flex items-center justify-start md:justify-start">
                                            {{ $i }}.
                                        </div>
                                        <div class="col-span-12 md:col-start-2 md:col-end-6 text-xl text-gray-900 my-auto">
                                            {{ $service->getTranslation('feature_title_' . $i, $lang) }}
                                        </div>
                                        <div
                                            class="col-span-12 md:col-start-6 md:col-end-13 text-base sm:text-lg text-gray-700">
                                            {{ $service->getTranslation('feature_content_' . $i, $lang) }}
                                        </div>
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>

    <section class="bg-[#f4f9ff] py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24">
        <div class="lg:w-5/6 mx-auto">
            <div class="mb-8">
                <h2 class="responsive-heading mb-4 text-center capitalize">
                    @php
                        $text = get_setting('why_choose_us_title');
                        $formattedText = preg_replace('/\[(.*?)\]/', '<span class="text-sky-400">$1</span>', $text);
                    @endphp
                    {!! $formattedText !!}

                    <p class="max-w-3xl font-light font-[--aspekta] text-gray-600 text-center mx-auto text-xl mb-10 mt-4">
                        {{ get_setting('why_choose_us_subtitle') }}
                    </p>
            </div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @for ($i = 1; $i <= 6; $i++)
                        @php
                            $title = get_setting('why_choose_us_title' . $i);
                            $content = get_setting('why_choose_us_subtitle' . $i);
                            $image = get_setting('why_choose_us_image' . $i);
                        @endphp
                        @if ($title || $content || $image)
                            <div class="swiper-slide">
                                <div class="bg-white p-8 text-center space-y-10 transition-shadow border border-[--dark]">
                                    <div class="w-fit mx-auto my-6">
                                        <img class="w-16" src="{{ uploaded_asset($image) }}" alt="{{ $title }}">
                                    </div>
                                    <div class="">
                                        <h3 class="text-xl text-gray-800 my-4">{{ $title ?? '' }}</h3>
                                        <p class="text-gray-600">{{ $content ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endfor
                         
                </div>
                <div class="swiper-pagination mt-8 relative"></div>
        
            </div>
    </section>

    <section class="py-16 px-6 ...">
        <div class="lg:w-5/6 mx-auto">
            <div class="max-w-4xl mx-auto relative">
                <h1 class="text-4xl text-gray-900 mb-10">{{ $page->getTranslation('heading2', $lang) }}</h1>
                <div class="space-y-6">
                    @forelse ($faq_categories[0]->faq_list as $faq)
                        <div class="border-b border-gray-200 pb-6">
                            <div class="flex justify-between items-center cursor-pointer" onclick="toggleAccordion(this)">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $faq->question }}</h3>
                                <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div
                                class="text-gray-600 leading-relaxed max-h-0 overflow-hidden transition-max-height duration-500 ease-in-out">
                                {{ $faq->answer }}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">No FAQs for this service yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>


    <section class="bg-[#f4f9ff] py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24">
        <div class="lg:w-5/6 mx-auto">

            <div class="max-w-lg mb-8">
                <h2 class="responsive-heading mb-4 capitalize">

                    @php
                        $text = $page->getTranslation('title2', $lang);
                        $formattedText = preg_replace('/\[(.*?)\]/', '<span class="text-sky-400">$1</span>', $text);
                    @endphp
                    {!! $formattedText !!}

                    </span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse($latestServices as $service)
                    <div
                        class="bg-white border border-gray-400 hover:border-[--dark] p-6 transform transition-all duration-300 hover:scale-102 hover:shadow-xl hover:-translate-y-3">
                        <div class="w-14 h-14 rounded-full bg-[--primary] flex items-center justify-center mb-5 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl text-gray-800 mb-2 md:min-h-[54px] lg:min-h-[68px] xl:min-h-max">{{ $service->name }}</h3>
                        <p class="text-gray-500 text-base font-normal mb-6 leading-tight md:min-h-[68px] lg:min-h-[100px] xl:min-h-[60px]">
                            {{ $service->getTranslation('short_description', $lang) }}
                        </p>
                        <ul class="space-y-3 text-base text-gray-700 mb-5">

                            @foreach (range(1, 6) as $i)
                                @if (!empty($service->getTranslation('feature_title_' . $i, $lang)))
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[--secondary] mr-2"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span>{{ $service->getTranslation('feature_title_' . $i, $lang) }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <a href="{{ route('services.show', $service->slug) }}"
                            class="mt-auto border border-[--dark] rounded-full px-6 py-3 text-[--dark] hover:bg-[--primary] transition hover:text-white hover:border-[--primary] w-max">
                            Learn More
                        </a>
                    </div>

                @empty
                    <div class="col-span-full text-center text-gray-600 text-lg mt-6">
                    </div>
                @endforelse
            </div>
        </div>
    </section>



@endsection
