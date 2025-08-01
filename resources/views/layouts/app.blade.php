<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ get_setting('site_icon') }}">
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script type="module" src="{{ asset('dist/assets/app-f10b86b9.js') }}"></script>
    {!! SEO::generate() !!}

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet" />

    @yield('style')


    <style>
        .menu-active {
            color: rgb(65 182 232 / var(--tw-text-opacity, 1));
        }

        .text-danger {
            color: red;
        }
    </style>
</head>

<body>

    @include('components.navigation.header')

    <main>
        @yield('content')
    </main>


    @include('components.navigation.footer')

    <div class="circle" id="cursorCircle"></div>


    @yield('script')

    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/scroll.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif

            @if (session('error'))
                toastr.error('{{ session('error') }}');
            @endif

            $(document).ready(function() {
                $('.counter-input').each(function() {
                    let productId = $(this).attr('id').split('_')[
                        1]; // Extract ID from `quantity-field_{id}`
                    let maxQuantity = parseInt($('.increment-button[data-id="' + productId + '"]')
                        .data('max-quantity')) || Infinity;
                    updateButtonState(productId, maxQuantity);
                });
            });

            $('#newsletter-form').on('submit', function(e) {
                e.preventDefault();

                let newsletter_email = $('#newsletter_email').val();
                let _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('newsletter.subscribe') }}",
                    type: "POST",
                    data: {
                        newsletter_email: newsletter_email,
                        _token: _token
                    },
                    success: function(response) {
                        $('#messageNewsletter').text(response.success).css('color', '#00dc00');
                        $('#newsletter_email').val('');
                    },
                    error: function(xhr) {
                        let error = xhr.responseJSON.errors.newsletter_email[0];
                        $('#messageNewsletter').text(error).css('color', 'red');
                    }
                });
            });


        });
    </script>
    <script>
        const menuButton = document.getElementById('menu-button');
        const drawerMenu = document.getElementById('drawer-menu');
        const drawerOverlay = document.getElementById('drawer-overlay');
        const closeMenuButton = document.getElementById('close-menu');

        // Open menu
        menuButton.addEventListener('click', () => {
            drawerMenu.classList.add('open');
            drawerOverlay.classList.remove('hidden');
        });

        // Close menu
        closeMenuButton.addEventListener('click', () => {
            drawerMenu.classList.remove('open');
            drawerOverlay.classList.add('hidden');
        });

        // Close menu when overlay is clicked
        drawerOverlay.addEventListener('click', () => {
            drawerMenu.classList.remove('open');
            drawerOverlay.classList.add('hidden');
        });
    </script>



</body>

</html>
