@props(['product'])

<div x-data="{ tab: 'tab_0' }" class="mt-10">
    <!-- Tab Navigation -->
    <div class="flex overflow-x-auto md:overflow-hidden font-semibold border border-gray-200 bg-primary/5 rounded-lg">
        @if (!empty($product['tabs']))
            @foreach ($product['tabs'] as $tabkey => $tab)
                <button 
                    @click="tab = 'tab_{{ $tabkey }}'" 
                    :class="{ 'border-b-2 border-primary text-primary' : tab === 'tab_{{ $tabkey }}' }"
                    class="flex-1 min-w-[150px] md:w-auto py-3 px-6 text-gray-600 hover:text-primary transition-all duration-300 text-center">
                    {{ $tab->heading }}
                </button>
            @endforeach
        @endif
    </div>

    <!-- Tab Content -->
    <div class="p-4 lg:p-6 border border-gray-200 rounded-b-lg mt-2">
        @if (!empty($product['tabs']))
            @foreach ($product['tabs'] as $tabkey => $tab)
                <div x-show="tab === 'tab_{{ $tabkey }}'" x-transition.opacity.duration.300ms>
                    <x-products.product-description :tab="$tab" />
                </div>
            @endforeach
        @endif
    </div>
</div>

