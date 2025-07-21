@props(['tab'])

<div class="w-full technical_specs">

    <h2 class="text-[20px] font-semibold text-gray-800 flex items-center gap-2 pb-4 pt-0 bg-gray-50 rounded-t-lg">
       {{ $tab['heading'] }}
    </h2>


    <p class="text-gray-700 mt-4 leading-relaxed ">
        {!! $tab['content'] ?? 'No description available.' !!}
        
    </p>

</div>
