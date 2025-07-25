      <section class="w-full py-10 bg-white">
          <div class="swiper logoSwiper">
              <div class="swiper-wrapper items-center">
                  @foreach ($partners as $partner)
                      <div class="swiper-slide flex justify-center items-center  p-5">
                          <img src="{{ uploaded_asset($partner['image']) }}"  alt="Partners Image" class="w-44 w-auto" />
                      </div>
                  @endforeach
              </div>
          </div>
      </section>
