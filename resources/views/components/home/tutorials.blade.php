       <section class="bg-[#f4f9ff] py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24 overflow-hidden">
           <div class="lg:w-5/6 mx-auto">
               <h2 class="responsive-heading leading-tight text-center text-gray-800 mb-4">

                   @php
                       $text = $page->getTranslation('title2', $lang);
                       $words = explode(' ', $text);

                       $formattedText = preg_replace(
                           '/\[(.*?)\]/',
                           '<span class="text-sky-400">$1</span>',
                           implode(' ', array_slice($words, 0, 3)) . '<br>' . implode(' ', array_slice($words,3)),
                       );

                   @endphp
                   {!! $formattedText !!}
               </h2>
               <p
                   class="max-w-3xl font-light font-[--aspekta] text-gray-600 text-center mx-auto text-base sm:text-lg md:text-xl mb-10">
                   {{ $page->getTranslation('content2', $lang) }}
               </p>
               <div class="relative">
                   <div class="swiper tutorials-swiper">
                       <div class="swiper-wrapper">

                           @foreach ($tutorials as $tutorial)
                               <div
                                   class="swiper-slide flex flex-col bg-white overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-3 group">
                                   <img src="{{ uploaded_asset($tutorial['image']) }}" alt="Tutorial Image"
                                       class="w-full object-cover aspect-square">
                                   <div
                                       class="p-6 flex-1 flex flex-col max-h-[130px] w-full transform transition duration-200 group-hover:-translate-y-20 bg-white">
                                       <a href="{{ route('tutorial.details', $tutorial['slug']) }}"
                                           class=" text-gray-800 mb-5  underline-offset-2 text-[22px] font-[--aspekta] font-normal   leading-7 block cursor-pointer">
                                           {{ $tutorial['name'] }}
                                       </a>
                                       <a href="{{ route('tutorial.details', $tutorial['slug']) }}"
                                           class="mt-auto border border-[--dark] rounded-full px-6 py-3 text-[--dark] hover:bg-[--primary] transition hover:text-white hover:border-[--primary] w-max opacity-0 group-hover:opacity-100">
                                           Learn More
                                       </a href="{{ route('tutorial.details', $tutorial['slug']) }}">
                                   </div>
                               </div>
                           @endforeach


                       </div>
                       <button
                           class="swiper-button-prev !left-0 !top-1/2 !-translate-y-1/2 bg-white shadow-lg rounded-full w-12 h-12 flex items-center justify-center absolute z-10">

                       </button>
                       <button
                           class="swiper-button-next !right-0 !top-1/2 !-translate-y-1/2 bg-white shadow-lg rounded-full w-12 h-12 flex items-center justify-center absolute z-10">

                       </button>
                   </div>
                   <div class="flex justify-center mt-10">
                       <a href="/tutorials"
                           class="bg-transparent relative group overflow-hidden transition-all duration-150 text-base sm:text-lg border border-[--dark] text-[--dark] px-6 py-3 sm:px-10 sm:py-4 rounded-full hover:bg-[--primary] hover:text-white hover:shadow-lg hover:-translate-y-1 w-auto sm:w-auto">
                           <!-- <span class="absolute bg-[--primary] translate-y-11 left-0 group-hover:-translate-y-4 transform transition-all duration-150 w-full h-full rounded-full"></span> -->
                           <span class="">View All</span>
                       </a>
                   </div>
               </div>
           </div>
       </section>
