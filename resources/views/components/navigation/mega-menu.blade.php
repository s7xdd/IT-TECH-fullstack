<div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
    <!-- Dropdown Trigger -->
    <button 
        @click="open = !open" 
        class="hover:text-primary flex items-center transition-colors duration-300 focus:outline-none"
    >
        {{ $title }} ▼
    </button>

    <!-- Dropdown Content -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
        @click.away="open = false"
        class="absolute bg-white shadow-xl mt-4 py-6 px-8 rounded-lg w-[1200px] left-1/2 -translate-x-1/2 z-[60]"
    >
        <div class="container mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-4">
                @foreach ($categories as $category)
                    <div class="col-span-1">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">{{ $category['name'] }}</h3>
                        @foreach ($category['products'] as $product)
                            <x-navigation.mega-menu-item 
                                :title="$product['title']" 
                                :description="$product['description']" 
                                :image="$product['image']"
                            />
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
