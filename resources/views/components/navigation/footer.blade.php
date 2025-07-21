  <footer class="bg-white lg:px-0 px-6">
      <div class="lg:w-5/6 mx-auto pb-10 pt-16 flex gap-6 flex-wrap">
          <div class="flex flex-col items-start gap-4 w-full xl:w-[200px] mb-4">
              <img src={{ uploaded_asset(get_setting('footer_logo')) }} alt="IT & Tech Logo" class="h-16 w-auto mb-2">
          </div>

          @foreach ($menu_items as $item)
              <div class="mb-8 md:mb-0 w-full sm:min-w-[200px] flex-1">
                  <div class="text-gray-800 mb-4 text-[18px] font-normal"> {{ $item->label }}</div>
                  <ul class="space-y-2 text-gray-600 font-light">
                      @if (isset($item->child) && $item->child->isNotEmpty())
                          @foreach ($item->child as $child)
                              <li><a href="{{ url($child->link) }}" class="hover:text-[--primary] hover:underline">
                                      {{ $child->label }}
                                  </a></li>
                          @endforeach
                      @endif
                  </ul>
              </div>
          @endforeach

          <div class="min-w-full sm:min-w-[200px] flex-1 ">
              <div class="text-gray-800 mb-4 text-[18px] font-normal">{{ get_setting('footer_contact_title') }}</div>
              <div class="text-gray-600 text-lg mb-6">
                  {{ get_setting('footer_address') }}
              </div>
              <div class="text-gray-800 mb-2 text-[18px] font-normal"> {{ get_setting('footer_newsletter_title') }}
              </div>

              <form id="newsletter-form" class="relative">
                  @csrf
                  <input type="email" id="newsletter_email" name="newsletter_email"
                      placeholder={{ get_setting('footer_newsletter_subtitle') }}
                      class="w-full border-b border-gray-300 bg-transparent py-4 px-0 focus:outline-none focus:border-[--primary] text-gray-700 mb-2 pr-12" />
                  <button type="submit" id="newsletter-send"
                      class="absolute right-0 top-1/2 -translate-y-1/2 bg-[--primary] rounded-full p-2 flex items-center justify-center transition-opacity duration-200 opacity-0 -translate-x-4"
                      style="pointer-events: none;">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none"
                          viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                      </svg>
                  </button>
                <p id="messageNewsletter" class="mt-2"></p>
              </form>

          </div>
      </div>

      <div
          class="lg:w-5/6 mx-auto border-t border-gray-200 py-8 flex flex-col xl:flex-row md:justify-between items-center text-gray-500 text-base">
          <div class="text-center xl:text-left">
              &copy; 2025 IT & Tech. All Rights Reserved. Designed by Tomsher
          </div>

          <div class="flex gap-6 mt-2 md:mt-0">
              @if (isset($bottom_footer) && count($bottom_footer) > 0)
                  @foreach ($bottom_footer as $item)
                      <a href="{{ url($item->link) }}" class="hover:text-[--primary]">{{ $item->label }}</a>
                  @endforeach
              @endif
          </div>

      </div>
  </footer>
