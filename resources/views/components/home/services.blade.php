   <section class="z-60 bg-white py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24">
       <div class="lg:w-5/6 mx-auto">
           <div class="flex relative">
               <div class="flex flex-col md:flex-row items-start md:items-center gap-10 w-full md:w-[60%] lg:w-[40%]">
                   <div class="max-w-full max-lg:max-w-xl">

                       <h2 class="responsive-heading leading-tight text-gray-800 mb-4">
                           @php
                               $text = $page->getTranslation('heading3', $lang);
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
                       </h2>
                       <p class="font-light font-[--aspekta] text-gray-600 text-base sm:text-lg md:text-xl mb-10">
                           {{ $page->getTranslation('heading4', $lang) }}
                       </p>
                       <a href="#" class="text-[--primary] text-[22px] font-[--aspekta] hover:underline">
                           {{ $page->getTranslation('heading8', $lang) }}
                       </a>
                   </div>
               </div>
               <div class="absolute right-0 top-0 w-fill lg:w-1/2 h-full flex items-center justify-center max-lg:hidden">
                   <div class="mt-[140px] flex justify-center opacity-100">
                       <video autoplay loop muted playsinline poster="assets/img/services-poster.jpg"
                           class="w-full max-w-2xl">
                           <source src="assets/vid/vid.webm" type="video/mp4">
                           Your browser does not support the video tag.
                       </video>
                   </div>
               </div>
           </div>
           <div
               class="grid [grid-template-columns:repeat(auto-fit,_minmax(280px,_1fr))] lg:[grid-template-columns:repeat(auto-fit,_minmax(380px,_1fr))] gap-8 mt-12">

               @forelse($services as $service)
                   <div
                       class="group hover:bg-[--primary] bg-white text-white p-8 flex flex-col justify-between min-h-[340px] h-full relative transition-all duration-300 overflow-hidden border border-gray-800">
                       <div class="relative z-10 flex flex-col justify-between h-full">
                           <div class="mb-6">
                               <img src="{{ uploaded_asset($service['icon']) }}" alt="Digital Transformation Icon"
                                   class="h-20 aspect-auto mb-4 transition-colors duration-300">
                           </div>
                           <div
                               class="absolute -bottom-16 group-hover:bottom-0 transition-all duration-300 text-[--dark] group-hover:text-white">
                               <a href="{{ route('services.show', ['slug' => $service['slug']]) }}"
                                   class="text-[22px] font-[--aspekta] font-normal  leading-7 underline-offset-4 block">
                                   {{ $service->getTranslation('name', $lang) }}
                               </a>
                               <p
                                   class="opacity-0 font-light text-base sm:text-lg group-hover:opacity-100 font-[--aspekta] transition-opacity duration-300 mt-3">
                                   {{ $service->getTranslation('short_description', $lang) }}
                               </p>
                           </div>
                       </div>


                       <button
                           class="border border-[--primary] group-hover:border-white rounded-full text-[--primary] absolute top-6 z-30 right-6 overflow-hidden transition-all duration-300 w-[52px] h-[52px] hover:w-[160px] lt-learn-more hover:bg-white">
                           <a href="{{ route('services.show', ['slug' => $service['slug']]) }}"
                               class="flex justify-between items-center group p-3.5 px-5">
                               <span
                                   class="transform hover:translate-x-0 text-base duration-300 whitespace-nowrap">Learn
                                   More</span>
                               <span
                                   class="w-10 group-hover:-rotate-90 transition-transform duration-300 absolute top-1 right-1 group-hover:text-white">
                                   <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 1920 1920">
                                       <path
                                           d="M915.744 213v702.744H213v87.842h702.744v702.744h87.842v-702.744h702.744v-87.842h-702.744V213z"
                                           fill-rule="evenodd" />
                                   </svg>
                               </span>
                           </a>
                       </button>

                   </div>
               @empty
                   <div class="col-span-full text-center text-gray-600 text-lg mt-6">
                       No services available at the moment.
                   </div>
               @endforelse

           </div>
       </div>
   </section>
