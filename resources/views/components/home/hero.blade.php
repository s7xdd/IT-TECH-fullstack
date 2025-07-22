    <section
        class="flex-grow flex flex-col text-center h-[740px] min-[560px]:h-[760px] min-[768px]:h-[800px] min-[1024px]:h-[930px] min-[1200px]:h-[920px] min-[1300px]:h-[1000px] min-[1600px]:h-[1080px] bg-[--primary] !overflow-hidden relative banner-area">

        <div
            class="w-[70%] content-wrapper text-center absolute z-10 top-[30%] space-y-3 xl:space-y-5 transition-all duration-150 left-1/2 -translate-x-1/2 transform -translate-y-1/2 hero-wrapper">
            <h1 id="heroText" class="font-medium gradient-text">
                @php
                    $text = $page->getTranslation('title', $lang);
                    $words = explode(' ', $text);
                    $firstLine = implode(' ', array_slice($words, 0, 3));
                    $secondLine = implode(' ', array_slice($words, 3));
                @endphp
                <span class="text-white">{{ $firstLine }}<br />{{ $secondLine }}</span>
            </h1>

            <p
                class="max-w-xl xl:max-w-2xl mx-auto text-center text-[18px] font-[--aspekta] font-light text-white opacity-70">
                {{ $page->getTranslation('sub_title', $lang) }}
            </p>

            <div class="flex sm:flex-row justify-center gap-4 font-[--aspekta] px-4 sm:px-0">
                <a href="{{ route('services.index') }}"
                    class="bg-white text-base whitespace-nowrap sm:text-lg text-[--dark] px-6 py-3 sm:px-6 xl:py-4 rounded-full hover:bg-gray-100 sm:w-auto">
                    {{ $page->getTranslation('heading1', $lang) }}
                </a>
                <a href="{{ route('contact') }}"
                    class="bg-transparent whitespace-nowrap transition-all duration-150 text-base sm:text-lg border border-white text-white px-6 py-3 sm:px-6 xl:py-4 rounded-full hover:bg-white hover:text-blue-800 sm:w-auto">
                    {{ $page->getTranslation('heading2', $lang) }}
                </a>
            </div>

        </div>

        <div
            class="absolute w-full px-6 md:w-3/4 lg:mx-auto z-10 bottom-0 left-1/2 -translate-x-1/2 rounded-t-[30px] overflow-hidden">

            <div class="relative bg-white bg-opacity-30 rounded-t-[30px] overflow-hidden shadow-lg backdrop-blur-md">
                <div
                    class="absolute left-0 right-0 bg-gradient-to-t from-black to-transparent pointer-events-none h-[45%] bottom-0 opacity-70">
                </div>

                <div id="slider-media"
                    class="w-full md:h-[450px] lg:h-[500px] xl:h-[550px] transition-all duration-150 flex items-center justify-center ">
                    <!-- Media will be injected here -->
                </div>

                <!-- Slider Controls -->
                <div class="absolute right-7 bottom-7 flex gap-3 z-50">
                    <button id="prev"
                        class="bg-transparent border border-white bg-opacity-70 rounded-full p-2 shadow hover:bg-opacity-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill=""
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button id="next"
                        class="bg-transparent border border-white bg-opacity-70 rounded-full p-2 shadow hover:bg-opacity-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Pause Button with Circular Progress -->
                <button id="pause"
                    class="absolute left-7 bottom-7 flex items-center justify-center w-12 h-12 rounded-full border border-white  transition z-20 shadow-lg overflow-visible">
                    <span class="flex items-center justify-center w-12 h-12 rounded-full z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" full="currentColor"
                            viewBox="0 0 24 24">
                            <rect x="7" y="5" width="3" height="14" rx="1.5" fill="white" />
                            <rect x="14" y="5" width="3" height="14" rx="1.5" fill="white" />
                            <circle cx="12" cy="12" r="11" stroke="white" stroke-width="2"
                                fill="none" />
                        </svg>
                    </span>
                    <svg class="absolute inset-0 w-full h-full pointer-events-none z-0" viewBox="0 0 64 64">
                        <circle cx="32" cy="32" r="28" stroke="#38bdf8" stroke-width="4" fill="none"
                            stroke-linecap="round" stroke-dasharray="175.93" stroke-dashoffset="0"
                            id="video-progress-circle" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="w-full h-full absolute">
            <img id="stripe-light" src="assets/img/bg-light.png" class="opacity-100 absolute bottom-0 left-0 right-0"
                alt="">

            <img id="bg-pattern" src="assets/img/bg-pattern.png"
                class="absolute h-full object-cover w-full bottom-0 left-0 right-0" alt="">
        </div>


    </section>

    <script>
        window.sliderMedia = [
            @foreach ($slider as $item)
                {
                    type: @if ($item->video_file || $item->video_url)
                        'video'
                    @else
                        'image'
                    @endif ,
                    name: "{{ $item->name }}",
                    src: @if ($item->video_file)
                        "{{ asset('storage/' . $item->video_file) }}"
                    @elseif ($item->video_url)
                        "{{ $item->video_url }}"
                    @else
                        "{{ uploaded_asset($item->image) }}"
                    @endif
                }
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
        ];

        // Ensure the DOM is fully loaded before running scripts
        window.addEventListener('DOMContentLoaded', () => {
            // --- GSAP Hero Content Animations ---
            // Using a timeline for hero animations improves sequencing and control
            const heroTimeline = gsap.timeline({
                delay: 0.2,
                defaults: {
                    ease: "power3.out"
                }
            });

            heroTimeline.to("#hero-content", {
                    opacity: 1,
                    y: 0,
                    duration: 1.2
                })
                .from("#hero-content h1", {
                    y: 40,
                    opacity: 0,
                    duration: 1
                }, "<0.2") // Starts 0.2 seconds after the previous animation ends
                .from("#hero-content p", {
                    y: 30,
                    opacity: 0,
                    duration: 0.8
                }, "<0.3") // Starts 0.3 seconds after the previous animation ends
                .from("#hero-content .flex button", {
                    y: 20,
                    opacity: 0,
                    duration: 0.7,
                    stagger: 0.15
                }, "<0.3"); // Starts 0.3 seconds after the previous animation ends

            // --- Parallax Scroll Effect ---
            // Use GSAP's `to` method directly in the scroll event for smoother animations
            const bgPattern = document.getElementById('bg-pattern');
            const stripeLight = document.getElementById('stripe-light');

            if (bgPattern || stripeLight) {
                window.addEventListener('scroll', () => {
                    const scrollY = window.scrollY; // window.pageYOffset is deprecated

                    if (bgPattern) {
                        gsap.to(bgPattern, {
                            y: scrollY * 0.4,
                            ease: "power1.out",
                            overwrite: "auto",
                            duration: 0.2
                        });
                    }
                    if (stripeLight) {
                        gsap.to(stripeLight, {
                            y: scrollY * 0.35,
                            ease: "none", // Linear movement for a consistent parallax
                            overwrite: "auto",
                            duration: 0.3
                        });
                    }
                });
            }


            // --- Stripe Light Blinking Effect ---
            // Use GSAP for animation for better performance and control
            if (stripeLight) {
                gsap.to(stripeLight, {
                    opacity: 0.8,
                    duration: 1, // Duration of one half of the blink
                    yoyo: true, // Go back and forth
                    repeat: -1, // Repeat indefinitely
                    ease: "sine.inOut" // Smooth in and out
                });
            }

            // --- Slider Logic ---
            const slides = window.sliderMedia;

            console.log("slides", slides);


            let current = 0;
            let isPaused = false;
            let autoAdvanceTimeout = null;

            const sliderMedia = document.getElementById('slider-media');
            const prevBtn = document.getElementById('prev');
            const nextBtn = document.getElementById('next');
            const pauseBtn = document.getElementById('pause');

            if (!sliderMedia || !prevBtn || !nextBtn || !pauseBtn) {
                console.warn("Slider elements not found. Skipping slider initialization.");
                return;
            }

            const getYouTubeEmbedUrl = (url) => {
                const decodedUrl = decodeHtmlEntities(url);

                const urlObj = new URL(decodedUrl);
                const videoId = urlObj.searchParams.get("v");

                const params = new URLSearchParams({
                    autoplay: 1,
                    mute: 1,
                    controls: 0,
                    modestbranding: 1,
                    rel: 0,
                    showinfo: 0,
                    iv_load_policy: 3,
                    fs: 0
                });

                return `https://www.youtube.com/embed/${videoId}?${params.toString()}`;
            };


            const clearSliderMedia = () => {
                sliderMedia.innerHTML = '';
            };

            const decodeHtmlEntities = (str) => {
                const txt = document.createElement('textarea');
                txt.innerHTML = str;
                return txt.value;
            };

            const createMediaElement = (slide) => {
                if (slide.type === 'video') {
                    if (slide.src.includes('youtube.com')) {
                        const iframe = document.createElement('iframe');
                        iframe.src = getYouTubeEmbedUrl(slide.src);
                        iframe.className = "w-full h-full object-cover";
                        iframe.allow =
                            "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
                        iframe.allowFullscreen = true;
                        return iframe;
                    } else {
                        const video = document.createElement('video');
                        video.src = slide.src;
                        video.className = "w-full h-full object-cover";
                        video.controls = false;
                        video.autoplay = !isPaused;
                        video.muted = true;
                        video.playsInline = true;
                        video.setAttribute('disablePictureInPicture', '');
                        video.setAttribute('controlsList', 'nodownload nofullscreen noremoteplayback');
                        video.addEventListener('contextmenu', e => e.preventDefault());
                        return video;
                    }
                } else if (slide.type === 'image') {
                    const img = document.createElement('img');
                    img.src = slide.src;
                    img.className = "w-full h-full object-cover";
                    img.alt = "Slider Image";
                    return img;
                }
                return null;
            };

            function showSlide(idx) {
                clearTimeout(autoAdvanceTimeout); // Always clear timeout when changing slides
                current = (idx + slides.length) % slides.length;
                const slide = slides[current];
                const media = createMediaElement(slide);

                if (!media) return;

                clearSliderMedia(); // Clear before appending to prevent flicker
                sliderMedia.appendChild(media);

                // Auto-advance logic
                if (!isPaused) {
                    if (slide.type === 'video') {
                        if (slide.src.includes('youtube.com')) {
                            // For YouTube, assume a fixed duration if not controllable
                            autoAdvanceTimeout = setTimeout(() => showSlide(current + 1), 10000);
                        } else {
                            media.onended = () => showSlide(current + 1);
                            media.play().catch(error => console.error("Video play failed:",
                                error)); // Add error handling for play
                        }
                    } else if (slide.type === 'image') {
                        autoAdvanceTimeout = setTimeout(() => showSlide(current + 1), 3000);
                    }
                }
                updatePauseButton();
            }

            const updatePauseButton = () => {
                pauseBtn.innerHTML = isPaused ?
                    `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polygon points="5,4 19,12 5,20" /></svg>` :
                    `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="6" y="4" width="4" height="16" rx="1" /><rect x="14" y="4" width="4" height="16" rx="1" /></svg>`;
            };

            prevBtn.addEventListener('click', () => showSlide(current - 1));
            nextBtn.addEventListener('click', () => showSlide(current + 1));
            pauseBtn.addEventListener('click', () => {
                isPaused = !isPaused;
                // When pausing/unpausing, re-render the current slide to apply the new state (e.g., video play/pause)
                showSlide(current);
            });

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    prevBtn.click();
                } else if (e.key === 'ArrowRight') {
                    nextBtn.click();
                } else if (e.key === ' ') {
                    e.preventDefault(); // Prevent spacebar from scrolling
                    pauseBtn.click();
                }
            });

            // Initial render
            showSlide(current);

            // --- Swiper Instances ---
            // Optimize Swiper initialization by making it more robust
            const initSwiper = (selector, options) => {
                const element = document.querySelector(selector);
                if (element) {
                    new Swiper(element, options);
                } else {
                    console.warn(`Swiper element not found for selector: ${selector}`);
                }
            };

            initSwiper('.tutorials-swiper', {
                slidesPerView: 1.2,
                spaceBetween: 24,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2.2
                    },
                    1024: {
                        slidesPerView: 3.2
                    }
                },
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                },
                on: {
                    init() {
                        this.el.style.overflow = 'visible';
                    }
                }
            });

            initSwiper(".logoSwiper", {
                loop: true,
                slidesPerView: 8,
                spaceBetween: 40,
                speed: 4000,
                freeMode: {
                    enabled: true,
                    momentum: false
                },
                autoplay: {
                    delay: 0,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: false
                },
                allowTouchMove: false,
                grabCursor: false,
                loopedSlides: 20,
                breakpoints: {
                    0: {
                        slidesPerView: 2,
                        spaceBetween: 16
                    },
                    480: {
                        slidesPerView: 3,
                        spaceBetween: 20
                    },
                    640: {
                        slidesPerView: 4,
                        spaceBetween: 24
                    },
                    768: {
                        slidesPerView: 5,
                        spaceBetween: 28
                    },
                    1024: {
                        slidesPerView: 6,
                        spaceBetween: 32
                    },
                    1280: {
                        slidesPerView: 7,
                        spaceBetween: 40
                    }
                },
                on: {
                    init() {
                        this.slides.forEach(slide => slide.style.pointerEvents = 'none');
                    },
                    click(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                }
            });
        });
    </script>
