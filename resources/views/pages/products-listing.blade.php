@extends('layouts.app')

@section('title', 'Products - '.env('APP_NAME'))

@section('content')
    <div class="container mx-auto py-12 px-6 lg:px-12">
        <!-- Section Title -->
        <h2 class="text-3xl md:text-4xl text-center">
            @php
                $text = $page->getTranslation('title', $lang); // Get this from DB
                $formattedText = preg_replace(
                    '/\[(.*?)\]/',
                    '<span class="text-primary font-semibold">$1</span>',
                    $text,
                );
            @endphp

            {!! $formattedText !!}
        </h2>
        <p class="text-gray-600 mt-2 text-center">
            {!! $page->getTranslation('sub_title', $lang) !!}
        </p>

        
        <!-- Filters Section -->
        <div x-data="{ open: false }" class="relative w-full mt-6">
            <div class="flex justify-between items-center">
                <button @click="open = true" class="px-5 py-2 bg-black text-white rounded-full shadow-md hover:bg-gray-800 transition">
                    Filter
                </button>
                <div>
                    <label for="sort_by" class="sr-only">Sort By</label>
                    <select id="sort-by" class="border border-gray-300 px-4 py-2 rounded-full text-gray-700 hover:bg-gray-100 transition" >
                        <option value="">Sort by</option>
                        <option value="name_asc" @if($sort_by == 'name_asc') selected @endif>A to Z</option>
                        <option value="name_desc" @if($sort_by == 'name_desc') selected @endif>Z to A</option>
                        <option value="latest" @if($sort_by == 'latest') selected @endif>Latest</option>
                        <option value="oldest" @if($sort_by == 'oldest') selected @endif>Oldest</option>
                        <option value="price_low" @if($sort_by == 'price_low') selected @endif>Price low to high</option>
                        <option value="price_high" @if($sort_by == 'price_high') selected @endif>Price high to low</option>
                    </select>
                </div>
            </div>

            <!-- Filters Drawer -->
            <div x-show="open" class="fixed inset-0 z-50 flex justify-end bg-black bg-opacity-50">
                <div class="bg-white w-80 p-6 h-full shadow-lg transform transition-transform duration-300" x-show="open"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                    <button @click="open = false" class="absolute top-4 right-4 text-gray-500 hover:text-gray-900">
                        ✖
                    </button>

                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Filters</h3>
                    <form action="" id="productfilters" method="GET">
                        <!-- Category Filter -->
                        
                        <input type="hidden" name="sort_by" id="sort_by" value="{{ $sort_by }}">
                        <label for="category" class="text-gray-700 font-medium">Category</label>
                    
                        @if (!empty($categories))
                            <select id="category" name="category" class="border border-gray-300 w-full px-4 py-2 rounded-lg mt-2">
                                <option value="">All Categories</option>
                                @foreach ($categories as $cat)
                                    <option value="{{$cat->getTranslation('slug', $lang)}}" @if($category == $cat->getTranslation('slug', $lang)) selected @endif>{{ $cat->getTranslation('name', $lang)}}</option>
                                    <!-- End collections -->
                                @endforeach
                                    
                            </select>
                        @endif

                        <!-- Discount Filter -->
                        <label for="discount" class="text-gray-700 font-medium mt-4 block">Brands</label>
                    
                        @if (!empty($brands))
                            <select id="brand" name="brand" class="border border-gray-300 w-full px-4 py-2 rounded-lg mt-2">
                                <option value="">All Brands</option>
                                @foreach ($brands as $brnd)
                                    <option value="{{$brnd->getTranslation('slug', $lang)}}" @if($brand == $brnd->getTranslation('slug', $lang)) selected @endif>{{ $brnd->getTranslation('name', $lang)}}</option>
                                @endforeach
                            </select>
                        @endif

                        <!-- Price Range Filter -->
                        <label for="price_range" class="text-gray-700 font-medium mt-4 block">Price Range</label>
                        <select id="price_range" name="price_range" class="border border-gray-300 w-full px-4 py-2 rounded-lg mt-2">
                            <option value="">Price Range</option>
                            <option @if($price == '1-1000') selected @endif value="1-1000">AED 1 - 1000</option>
                            <option @if($price == '1001-2000') selected @endif value="1001-2000">AED 1001 - 2000</option>
                            <option @if($price == '2001-3000') selected @endif value="2001-3000">AED 2001 - 3000</option>
                            <option @if($price == '3001-4000') selected @endif value="3001-4000">AED 3001 - 4000</option>
                            <option @if($price == '4001-200000') selected @endif value="4001-200000">More than AED 4000</option>
                        </select>

                        <button class="w-full mt-6 py-2 bg-[#41B6E8] text-white rounded-lg hover:bg-[#3498db] transition">
                            Apply Filters
                        </button>
                       
                    </form>
                    <a href="{{ route('products.index') }}">
                        <button class="w-full mt-6 py-2 bg-[#4d4d4f] text-white rounded-lg hover:bg-[#4d4d4fbf]">
                            Clear Filters
                        </button>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-5 2xl:grid-cols-5 gap-x-[20px] gap-y-[30px] mt-[40px]" id="product-container">
            @if(!empty($products[0]))
                @foreach ($products as $product)
                    @php
                        $priceData = getProductOfferPrice($product);
                    @endphp
                    <div class="relative group cursor-pointer bg-transparent transition duration-300 overflow-hidden rounded-lg">
                        <a href="{{ route('products.show',['slug' => $product->slug]) }}" class="block">
                            <div class="overflow-hidden rounded-lg">
                                <img src="{{ get_product_image($product->thumbnail_img,'300') }}" alt="{{ $product->getTranslation('name', $lang) }}" class="w-full object-cover rounded-lg transition-transform duration-[400ms] ease-in-out group-hover:scale-[1.05]">
                            </div>
                        </a>

                        <div class="absolute bottom-[55px] left-[10px] right-[10px] flex justify-between items-center py-[6px] bg-transparent z-[1]">
                            <span class="text-[#41B6E8] py-[7px] px-[10px] rounded-full font-semibold text-sm shadow-sm bg-white/80 backdrop-blur-sm">
                                {{ env('DEFAULT_CURRENCY').' '.$priceData['discounted_price'] }}
                                @if ($priceData['discounted_price'] != $priceData['original_price'])
                                    <span class="text-gray-500 line-through"> &nbsp;{{ env('DEFAULT_CURRENCY').' '.$priceData['original_price'] }}</span>
                                @endif
                            </span>
                            
                                <x-buttons.add_to_cart :slug="$product->slug" :sku="$product->sku"/>
                            
                        </div>

                        <a href="{{ route('products.show',['slug' => $product->slug]) }}">
                            <p class="mt-[10px] text-left text-sm font-medium text-gray-800">{{ $product->getTranslation('name', $lang) }}</p>
                        </a>
                    </div>
                @endforeach
            @endif

        </div>

        <div id="load-more-trigger" class="h-10"></div>
       
        <!-- Loading Spinner (Positioned Below Product List) -->
        <div id="loading-indicator" style="display: none;" class="flex flex-col items-center justify-center w-full mt-6">
            <p class="text-gray-700 text-lg font-semibold mb-2">Loading...</p>
            <img src="{{ asset('assets/images/spinner.gif') }}" alt="Loading" class="w-20 h-20"/>
        </div>
       
        <div id="no-more-data" style="display: none;" class=" text-center text-gray-600 text-lg font-semibold mt-4 p-3 bg-gray-100 rounded-lg border border-gray-300">
            🚨 No more products available.
        </div>

    </div>
@endsection

@section('script')
<script>
    
    document.addEventListener("DOMContentLoaded", function () {
        let page = 1;
        let isLoading = false;
        let hasMoreData = true;

        const observer = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting && !isLoading && hasMoreData) {
                loadMoreData();
            }
        }, { threshold: 1 });

        observer.observe(document.getElementById("load-more-trigger"));

        function loadMoreData() {
            page++; // Increment page number

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('product.loadMore') }}",
                type: 'GET',
                data: {
                    page: page,
                    sort_by : $('#sort_by').val(),
                    category : $('#category').val(),
                    brand : $('#brand').val(),
                    price_range : $('#price_range').val(),
                    search : $('#search-input').val()
                },
                beforeSend: function() {
                    $('#loading-indicator').show(); // Show loading icon
                },
                success: function(response) {
                    if (response.html) {
                        // Append new blogs to the existing blog container
                        document.getElementById('product-container').innerHTML += response.html;
                    }else{
                        hasMoreData = false; // No more data, stop further requests
                        $('#no-more-data').show(); // Show a message if needed
                    }
                },
                complete: function() {
                    $('#loading-indicator').hide(); // Hide loading icon
                }
            });
        }

        $('#sort-by').on('change', function () {
            var sort = $(this).val();
            $('#sort_by').val(sort);
            $('#productfilters').submit();
        });

    });
</script>
@endsection
