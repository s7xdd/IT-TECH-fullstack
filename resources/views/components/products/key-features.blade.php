@props(['product'])

<div class="bg-white">

    <h2 class="text-[20px] font-semibold text-gray-800 flex items-center gap-2 pb-4 pt-0 bg-gray-50 rounded-t-lg">
    Key Features
    </h2>


    <ul class="list-disc pl-5 text-gray-700 space-y-2">
        @foreach ($product['key_features'] as $feature)
            <li>{{ $feature }}</li>
        @endforeach
    </ul>
</div>
