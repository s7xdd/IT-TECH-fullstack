<div class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-md transition-all duration-200">
    <img src="{{ asset('images/' . $image) }}" alt="{{ $title }}" class="w-12 h-12 object-cover rounded-lg">
    <div>
        <h4 class="text-sm font-semibold text-gray-800">{{ $title }}</h4>
        <p class="text-xs text-gray-600">{{ $description }}</p>
    </div>
</div>
