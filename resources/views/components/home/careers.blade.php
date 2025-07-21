 <section class="lg:px-0 px-6">
     <div
         class="lg:w-5/6 mx-auto py-20 bg-gradient-to-br from-blue-900 via-blue-800 to-cyan-500 relative overflow-hidden">
         <div class="mx-auto px-6 flex flex-col items-center justify-center">
             <div class="relative z-10 text-center">

                 <h2 class="text-3xl md:text-[40px] leading-tight text-[--white] mb-10">
                     @php
                         $text = $page->getTranslation('content', $lang);
                         $formattedText = preg_replace(
                             '/\[(.*?)\]/',
                             '<span class="text-cyan-200 font-normal">$1</span>',
                             $text,
                         );
                     @endphp
                     {!! $formattedText !!}
                 </h2>
                 <a href="{{ route('contact') }}"
                     class="bg-white text-base sm:text-lg text-[--dark] px-6 py-3 sm:px-6 sm:py-4 rounded-full hover:bg-gray-100 w-auto sm:w-auto">
                     Apply Now
                 </a>
             </div>
             <div class="absolute inset-0 pointer-events-none z-0">
                 <svg width="100%" height="100%" class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                     <defs>
                         <pattern id="dots" x="0" y="0" width="24" height="24"
                             patternUnits="userSpaceOnUse">
                             <circle cx="2" cy="2" r="2" fill="#ffffff22" />
                         </pattern>
                     </defs>
                     <rect width="100%" height="100%" fill="url(#dots)" />
                 </svg>
             </div>
         </div>
     </div>
 </section>
