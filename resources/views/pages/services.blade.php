@extends('layouts.app')

@section('title', 'Our Services - ' . env('APP_NAME'))

@section('content')
    <section class="py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24 relative z-10 mt-[84px]">
        <div class="flex lg:w-5/6 mx-auto">
            <div class="max-w-xl">
                <span class="bg-gray-200 rounded-full py-2 px-3">Our Services</span>
                <h1 class="responsive-heading my-4">
                    @php
                        $text = $page->getTranslation('title', $lang);
                        $words = explode(' ', $text);

                        $formattedText = preg_replace(
                            '/\[(.*?)\]/',
                            '<span class="text-sky-400">$1</span>',
                            implode(' ', array_slice($words, 0, 2)) . '<br>' . implode(' ', array_slice($words, 2)),
                        );

                    @endphp
                    {!! $formattedText !!}
                </h1>
                <p class="font-light font-[--aspekta] text-gray-600 text-xl mb-10">
                    {{ $page->getTranslation('sub_title', $lang) }}
                </p>
                <button
                    class="bg-transparent transition-all duration-150 text-base sm:text-lg border border-[--dark] text-[--dark] px-6 py-3 sm:px-10 sm:py-4 rounded-full  hover:text-white hover:bg-[--primary] hover:hover:-translate-y-1 w-auto sm:w-auto z-10">
                    View All
                </button>
            </div>
            <div>

            </div>
        </div>
    </section>

    <section class="bg-[#f4f9ff] py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24">
        <div class="lg:w-5/6 mx-auto">
            <div class="max-w-lg">
                <h2 class="responsive-heading mb-4">
                    {{ $page->getTranslation('heading1', $lang) }}
                </h2>
                <p class="font-light font-[--aspekta] text-gray-600 text-xl mb-10">
                    {{ $page->getTranslation('heading2', $lang) }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse($services as $service)

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
                        <h3 class="text-2xl text-gray-800 mb-2">{{ $service->name }}</h3>
                        <p class="text-gray-500 text-base font-normal mb-6 leading-tight">
                            {{ $service->getTranslation('short_description', $lang) }}
                        </p>
                        <ul class="space-y-3 text-base text-gray-700 mb-5">

                            @for ($i = 1; $i <= 6; $i++)
                                @if (
                                    !empty($service->getTranslation('feature_title_' . $i, $lang)) ||
                                        !empty($service->getTranslation('feature_content_' . $i, $lang)))
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
                            @endfor
                        </ul>
                        <a href="{{ route('services.show', $service->slug) }}"
                            class="mt-auto border border-[--dark] rounded-full px-6 py-3 text-[--dark] hover:bg-[--primary] transition hover:text-white hover:border-[--primary] w-max">
                            Learn More
                        </a>
                    </div>

            </div>
        </div>
    </section>
@endsection


@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let page = 1;
            let isLoading = false;
            let hasMoreData = true;

            const observer = new IntersectionObserver(entries => {
                if (entries[0].isIntersecting && !isLoading && hasMoreData) {
                    loadMoreData();
                }
            }, {
                threshold: 1
            });

            observer.observe(document.getElementById("load-more-trigger"));

            function loadMoreData() {
                page++; // Increment page number

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('services.loadMore') }}",
                    type: 'GET',
                    data: {
                        page: page
                    },
                    beforeSend: function() {
                        $('#loading-indicator').show(); // Show loading icon
                    },
                    success: function(response) {
                        if (response.html) {
                            // Append new services to the existing services container
                            document.getElementById('services-container').innerHTML += response.html;
                        } else {
                            hasMoreData = false; // No more data, stop further requests
                            $('#no-more-data').show(); // Show a message if needed
                        }
                    },
                    complete: function() {
                        $('#loading-indicator').hide(); // Hide loading icon
                    }
                });
            }

        });
    </script>
@endsection
