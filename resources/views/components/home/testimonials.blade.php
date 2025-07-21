<div class="py-12 bg-gray-50">
    <div class="container mx-auto px-6 lg:px-12">
        <!-- Section Title -->
        <h2 class="text-3xl md:text-4xl font-normal text-center">
            @php
                $text = $page->getTranslation('heading8',$lang)    ; // Get this from DB
                $formattedText = preg_replace('/\[(.*?)\]/', '<span class="text-primary font-semibold">$1</span>', $text);
            @endphp

            {!! $formattedText !!}
        </h2>


        <p class="text-gray-600 mt-2 text-center">
            {{ $page->getTranslation('content',$lang) }}
        </p>

        <!-- Testimonials Slider -->
        <div class="mt-10 relative">
            <div class="swiper testimonials-swiper">
            <div class="swiper-wrapper">
                    @foreach($testimonials as $testimonial)
                        <div class="swiper-slide flex justify-center items-center">
                            <!-- Testimonial Card -->
                            <div class="bg-gray-100 p-8 rounded-xl shadow-lg max-w-5xl mx-auto text-center flex flex-col items-center">
                                <!-- Quotation Icon -->
                                <div class="w-12 h-12 flex items-center justify-center rounded-full  mb-4">
                                <svg id="fi_7350738" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m19.3 6.3c1 1.1 1.6 2.3 1.6 4.3 0 3.5-2.5 6.6-6 8.2l-.9-1.3c3.3-1.8 4-4.1 4.2-5.6-.5.3-1.2.4-1.9.3-1.8-.2-3.2-1.6-3.2-3.5 0-.9.4-1.8 1-2.5.7-.7 1.5-1 2.5-1 1.1 0 2.1.5 2.7 1.1zm-10 0c1 1.1 1.6 2.3 1.6 4.3 0 3.5-2.5 6.6-6 8.2l-.9-1.3c3.3-1.8 4-4.1 4.2-5.6-.5.3-1.2.4-1.9.3-1.8-.2-3.2-1.7-3.2-3.5 0-.9.4-1.8 1-2.5.7-.7 1.5-1 2.5-1 1.1 0 2.1.5 2.7 1.1z"></path></svg>
                                </div>
                                <!-- Testimonial Content -->
                                <h3 class="text-lg font-semibold text-gray-800">{{ $testimonial->title }}</h3>
                                <p class="text-gray-600 mt-4 leading-relaxed">{{ $testimonial->comment }}</p>
                                <div class="mt-6 text-gray-800 font-semibold">{{ $testimonial->name }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Navigation Arrows -->
                <div class="absolute z-50 left-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white rounded-full w-[25px] h-[25px] md:w-[30px] md:h-[30px] lg:w-[50px] lg:h-[50px] xl:w-[50px] xl:h-[50px] flex items-center justify-center cursor-pointer shadow-lg prev hover:bg-gray-700 transition-all  duration-300">
                    <svg fill="white" height="30" viewBox="0 0 24 24" width="30" xmlns="http://www.w3.org/2000/svg" id="fi_2722991"><g id="_17" data-name="17"><path d="m15 19a1 1 0 0 1 -.71-.29l-6-6a1 1 0 0 1 0-1.41l6-6a1 1 0 0 1 1.41 1.41l-5.29 5.29 5.29 5.29a1 1 0 0 1 -.7 1.71z"></path></g></svg>
                </div>
                <div class="absolute z-50 right-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white rounded-full w-[25px] h-[25px] md:w-[30px] md:h-[30px] lg:w-[50px] lg:h-[50px] xl:w-[50px] xl:h-[50px] flex items-center justify-center cursor-pointer shadow-lg next hover:bg-gray-700 transition-all duration-300">
                    
                    <svg fill="white" height="30" width="30" viewBox="0 0 6.3499999 6.3500002" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg"><g id="layer1" transform="translate(0 -290.65)"><path id="path9429" d="m2.2580394 291.96502a.26460982.26460982 0 0 0 -.1741496.46871l1.6190225 1.38699-1.6190225 1.38648a.26460982.26460982 0 1 0 .3436483.40049l1.8536335-1.58595a.26460982.26460982 0 0 0 0-.40256l-1.8536335-1.5875a.26460982.26460982 0 0 0 -.1694987-.0667z" font-variant-ligatures="normal" font-variant-position="normal" font-variant-caps="normal" font-variant-numeric="normal" font-variant-alternates="normal" font-feature-settings="normal" text-indent="0" text-align="start" text-decoration-line="none" text-decoration-style="solid" text-decoration-color="rgb(0,0,0)" text-transform="none" text-orientation="mixed" white-space="normal" shape-padding="0" isolation="auto" mix-blend-mode="normal" solid-color="rgb(0,0,0)" solid-opacity="1" vector-effect="none"></path></g></svg>
                
                </div>
            </div>
        </div>
    </div>
</div>
