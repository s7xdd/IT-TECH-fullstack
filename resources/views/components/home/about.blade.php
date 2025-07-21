    <section class="py-16 px-6 sm:py-20 lg:px-0 md:py-24 lg:py-24 xl:py-24 relative z-10 bg-white overflow-hidden">
        <div class="flex flex-col items-center justify-center">
            <h2 id="about-title"
                class="text-4xl md:text-[52px] leading-[40px] md:leading-[50px] lg:leading-[58px] text-center font-normal text-gray-800 mb-4">
                @php
                    $text = $page->getTranslation('heading5', $lang); 
                    $formattedText = preg_replace('/\[(.*?)\]/', '<span class="text-sky-400">$1</span>', $text);
                @endphp

                {!! $formattedText !!}
            </h2>
            <p id="about-desc"
                class="max-w-4xl mx-auto font-light font-[--aspekta] text-center text-gray-600 text-base sm:text-lg md:text-xl mb-10">
                {{ $page->getTranslation('heading6', $lang) }}
            </p>
            <button
                class="bg-transparent transition-all duration-150 text-base sm:text-lg border border-[--dark] text-[--dark] px-6 py-3 sm:px-6 sm:py-4 rounded-full  hover:text-white hover:bg-[--primary] hover:shadow-lg hover:-translate-y-1 w-auto sm:w-auto z-10">
                Learn More
            </button>
        </div>

        <img id="about-bg-img" src="assets/img/about-bg.png"
            class="absolute bottom-0 left-0 right-0 transition-opacity duration-700" alt="">

    </section>
