@props(['product'])

<div class="">
    <h2 class="text-[20px] font-semibold text-gray-800 flex items-center gap-2 pb-4 pt-0 bg-gray-50 rounded-t-lg">
        Product Specifications
    </h2>

    <div class="overflow-hidden rounded-b-lg border border-gray-200">
        <table class="w-full border-collapse text-gray-700">
            <tbody>
                @foreach (['Category' => 'category', 'Weight' => 'weight', 'Dimensions' => 'dimensions', 'Color Options' => 'color_options', 'Material' => 'material'] as $label => $key)
                    <tr class="border-b hover:bg-indigo-50 transition">
                        <td class="p-4 font-semibold border-r w-1/3 bg-gray-100">{{ $label }}</td>
                        <td class="p-4">{{ $product[$key] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
