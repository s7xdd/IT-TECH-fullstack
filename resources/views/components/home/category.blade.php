@props(['categories', 'page','lang'])

<section id="categories-section" class="text-center pt-5 pb-5">

    <x-wrapper>
        <h2 class="text-3xl md:text-4xl font-normal">
            @php
                $text = $page->getTranslation('title',$lang)    ; // Get this from DB
                $formattedText = preg_replace('/\[(.*?)\]/', '<span class="text-primary font-semibold">$1</span>', $text);
            @endphp

            {!! $formattedText !!}
        </h2>
        <p class="text-gray-600 mt-4">
            {{ $page->getTranslation('sub_title',$lang) }}
        </p>

        <!-- Swiper Container -->
        <div id="category-swiper" class="swiper-container mt-10">
            <div class="swiper-wrapper">
                @foreach($categories as $category)
                    <div class="swiper-slide flex flex-col items-center group">
                        <!-- Image Wrapper with Hover Border -->
                        <a href="{{ route('products.index',['category' => $category->getTranslation('slug', $lang)]) }}">
                            <div class="relative rounded-full p-[5px] bg-transparent transition-all duration-300 border-[3px] border-gray-300 group-hover:border-[#41B6E8]">
                                <img src="{{ uploaded_asset($category->getTranslation('icon', $lang)) }}" 
                                    class="w-[100px] h-[100px] rounded-full object-cover transition-transform duration-300 group-hover:scale-105" 
                                    alt="{{ $category->getTranslation('name', $lang) }}">
                            </div>
                            <p class="mt-4 text-gray-700 font-medium">{{ $category->getTranslation('name', $lang) }}</p>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Swiper Pagination -->
            <div id="category-swiper-pagination" class="swiper-pagination"></div>
        </div>
    </x-wrapper>
</section>
