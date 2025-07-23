@extends('layouts.app')

@section('title', 'Blog - ' . env('APP_NAME'))

@section('content')
    <section class="py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24 relative z-10 mt-[84px]">
        <div class="flex lg:w-5/6 mx-auto">
            <div class="max-w-xl">
                <span class="bg-gray-200 rounded-full py-2 px-3">Our News</span>
                <h1 class="responsive-heading my-4">
                    @php
                        $text = $page->getTranslation('title', $lang);
                        $words = explode(' ', $text);

                        $formattedText = preg_replace(
                            '/\[(.*?)\]/',
                            '<span class="text-sky-400">$1</span>',
                            implode(' ', array_slice($words, 0, 3)) . '<br>' . implode(' ', array_slice($words, 3)),
                        );

                    @endphp
                    {!! $formattedText !!}
            </div>
            <div>

            </div>
        </div>
    </section>

    <section class="py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24 relative z-10 bg-[#f4f9ff]">
        <div class="lg:w-5/6 mx-auto">
            <div id="blog-container" class="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8">

                @foreach ($blogs as $blog)
                    <div class="bg-white p-5 hover:shadow-lg hover:-translate-y-3 duration-300 group border border-[--dark]">
                        <img src="{{ uploaded_asset($blog->image) }}" class="h-[250px] w-full object-cover"
                            alt="{{ $blog->name }}">
                        <div class="mt-6 mb-3">
                            <span class="text-gray-500">
                                {{ \Carbon\Carbon::parse($blog['blog_date'])->format('j F Y') }}
                            </span>
                            <a href="{{ route('blog.details', $blog['slug']) }}">
                                <p class="mb-5 text-xl hover:underline underline-offset-2">
                                    {{ $blog->name }}
                                </p>
                            </a>
                            <a href="{{ route('blog.details', $blog['slug']) }}"
                                class="mt-auto border border-black rounded-full px-6 py-3 text-[--dark] group-hover:bg-[--primary] group-hover:border-[--primary] group-hover:text-white transition w-max group-hover:opacity-100 hover:shadow-lg">
                                Learn More
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div id="load-more-trigger" class="h-10"></div>

        <div id="loading-indicator" style="display: none;"
            class="fixed inset-0 flex flex-col items-center justify-center bg-white bg-opacity-70 z-50">
            <p class="text-gray-700 text-lg font-semibold mb-2">Loading...</p>
            <img src="{{ asset('assets/images/spinner.gif') }}" alt="Loading" class="w-20 h-20" />
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
                page++;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('blog.loadMore') }}",
                    type: 'GET',
                    data: {
                        page: page
                    },
                    beforeSend: function() {
                        $('#loading-indicator').show();
                    },
                    success: function(response) {
                        if (response.html) {
                            document.getElementById('blog-container').innerHTML += response.html;
                        } else {
                            hasMoreData = false;
                            $('#no-more-data').show();
                        }
                    },
                    complete: function() {
                        $('#loading-indicator').hide();
                    }
                });
            }

        });
    </script>
@endsection
