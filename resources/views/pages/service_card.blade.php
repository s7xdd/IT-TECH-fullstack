@php
    $lang = getActiveLanguage();
@endphp

@foreach($services as $service)
    <div class="group p-6 bg-[#f7f7f7] rounded-2xl transition duration-300 border border-gray-200 hover:border-primary relative overflow-hidden">
        
        <!-- Service Image -->
        <div class="relative w-full h-48 overflow-hidden rounded-lg">
            @if($service->image != NULL)
                <img src="{{ uploaded_asset($service->image) }}" 
                     alt="{{ $service->getTranslation('name', $lang) }}" 
                     class="w-full h-full object-cover rounded-lg transition duration-300 transform group-hover:scale-105">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-200 rounded-lg">
                    <span class="text-gray-500">No Image Available</span>
                </div>
            @endif
        </div>

        <!-- Service Info -->
        <div class="mt-4 relative z-10">
            <h3 class="text-lg font-semibold text-black group-hover:text-primary transition duration-300">
                {{ $service->getTranslation('name', $lang) }}
            </h3>
        </div>

        <p class="mt-3 text-sm text-gray-700 relative z-10">
            {{ Str::limit($service->getTranslation('short_description', $lang), 200, '...') }}
        </p>

        <!-- Service Price & Buttons -->
        <div class="flex justify-between items-center mt-5 relative z-10">
            @if ($service->price != 0 && $service->price != NULL)
                <span class="text-[#41B6E8] py-2 px-4 rounded-full font-semibold text-sm shadow-sm bg-gray-100">
                    {{ env('DEFAULT_CURRENCY') }} {{ number_format($service->price, 2) }}
                </span>
            @endif

            <div class="flex items-center space-x-2">
                <a href="{{ route('services.show', ['slug' => $service->slug]) }}"
                   class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-600 rounded-full transition duration-300 hover:bg-gray-800 hover:text-white">
                    Learn More
                </a>
            </div>
        </div>

        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#E0F7FA] opacity-0 group-hover:opacity-70 transition duration-300 -z-10 rounded-xl"></div>
    </div>
@endforeach
