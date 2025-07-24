  <section class="bg-white py-16 px-6 sm:py-20 md:px-6 lg:px-0 md:py-24 lg:py-24 xl:py-24">
      <div class="lg:w-5/6 mx-auto">
          <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between mb-12">
              <div>
                  <h2 class="responsive-heading leading-tight text-gray-800 mb-4">

                      @php
                          $text = $page->getTranslation('title2', $lang);
                          $words = explode(' ', $text);

                          $formattedText = preg_replace(
                              '/\[(.*?)\]/',
                              '<span class="text-sky-400">$1</span>',
                              implode(' ', array_slice($words, 0, 3)) . '<br>' . implode(' ', array_slice($words, 3)),
                          );

                      @endphp
                      {!! $formattedText !!}

                  </h2>
                  <p class="max-w-3xl font-light font-[--aspekta] text-gray-600 mx-auto text-base sm:text-lg md:text-xl">
                      {{ $page->getTranslation('content5', $lang) }}
                  </p>
              </div>
              <div class="mt-6 md:mt-0">
                  <a href="{{ route('blog.index') }}"
                      class="bg-transparent transition-all duration-150 text-base sm:text-lg border border-[--dark] text-[--dark] px-6 py-3 sm:px-10 sm:py-4 rounded-full  hover:text-white hover:bg-[--primary] hover:shadow-lg hover:-translate-y-1 w-auto sm:w-auto z-10">
                      View All
                  </a>
              </div>
          </div>

          <div class="flex flex-col lg:flex-row gap-4">

              @php
                  $blogSectionOne = $blogs->first();
                  $blogsSectionTwo = $blogs->skip(1)->take(2);
                  $blogSectionThree = $blogs->skip(3)->take(1);

              @endphp
              <div class="w-full lg:w-3/6 bg-gray-500 relative group">
                  <img src="{{ uploaded_asset($blogSectionOne['image']) }}" alt="Empowering Digital Growth"
                      class="w-full h-full object-cover shadow-lg transform  max-h-[400px] lg:max-h-full">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent rounded-xl"></div>
                  <div class="absolute top-6 left-6 right-6 z-10">
                      <div class="text-gray-200 text-sm mb-2">
                          {{ \Carbon\Carbon::parse($blogSectionOne['blog_date'])->format('j F Y') }}</div>
                      <a href="{{ route('blog.details', $blogSectionOne['slug']) }}" class="text-white text-2xl font-semibold mb-4 leading-snug">
                          {{ $blogSectionOne['name'] }}
                      </a>

                  </div>
                  <a href="{{ route('blog.details', $blogSectionOne['slug']) }}"
                      class="absolute scale-100 group-hover:scale-110 duration-300 bottom-10 right-10 border border-white group-hover:bg-white hover:text-black rounded-full p-3 transition group">
                      <svg xmlns="http://www.w3.org/2000/svg"
                          class="h-6 w-6 text-white group-hover:text-black duration-300" fill="none"
                          viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                      </svg>
                  </a>
              </div>

              <div class="grid grid-cols-2 gap-4 w-full lg:w-3/6 relative">

                  @foreach ($blogsSectionTwo as $blog)
                      <div class="relative bg-gray-500 aspect-square overflow-hidden group ">
                          <img src="{{ uploaded_asset($blog['image']) }}" alt="Cybersecurity Focus"
                              class="w-full h-full aspect-square object-cover shadow-lg">
                          <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                          <div class="absolute top-6 left-6 right-6 z-10">
                              <div class="text-gray-200 text-sm mb-2">
                                  {{ \Carbon\Carbon::parse($blog['blog_date'])->format('j F Y') }}</div>
                              <a href="{{ route('blog.details', $blog['slug']) }}" class="text-white text-lg font-semibold mb-4 leading-snug line-clamp-3">
                                  {{ $blog['name'] }}
                              </a>
                          </div>

                          <a href="{{ route('blog.details', $blog['slug']) }}"
                              class="absolute transform scale-100 group-hover:scale-110 duration-300 bottom-6 right-6 border border-white group-hover:bg-white rounded-full p-2 transition">
                              <svg xmlns="http://www.w3.org/2000/svg"
                                  class="h-5 w-5 text-white group-hover:text-black duration-300" fill="none"
                                  viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5l7 7-7 7" />
                              </svg>
                          </a>
                      </div>
                  @endforeach

                  <div class="col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-0 bg-gray-500 overflow-hidden group">
                      @foreach ($blogSectionThree as $blog)
                          <div class="bg-blue-900 text-white flex flex-col justify-between p-8">
                              <div>
                                  <div class="text-gray-200 text-sm mb-2">
                                      {{ \Carbon\Carbon::parse($blog['blog_date'])->format('j F Y') }}</div>
                                  <a href="{{ route('blog.details', $blog['slug']) }}" class="text-xl font-semibold mb-4 leading-snug">
                                      {{ $blog['name'] }}
                                  </a>
                              </div>
                              <a href="{{ route('blog.details', $blog['slug']) }}"
                                  class="self-end border border-white transform scale-100 group-hover:scale-110 duration-300 group-hover:bg-white rounded-full p-2 transition">
                                  <svg xmlns="http://www.w3.org/2000/svg"
                                      class="h-5 w-5 text-white group-hover:text-black duration-300" fill="none"
                                      viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5l7 7-7 7" />
                                  </svg>
                              </a>
                          </div>
                          <img src="{{ uploaded_asset($blog['image']) }}" alt="Expanding IT Footprint"
                              class="w-full h-full object-cover aspect-square md:block rounded-r-xl max-h-[200px] sm:max-h-full">
                      @endforeach

                  </div>

              </div>
          </div>

      </div>
  </section>
