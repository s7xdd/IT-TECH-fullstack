@foreach ($tutorials as $tutorial)
    <div class="bg-white p-5 hover:shadow-lg hover:-translate-y-3 duration-300 group border border-[--dark]">
        <img src="{{ uploaded_asset($tutorial->image) }}" class="h-[250px] w-full object-cover" alt="{{ $tutorial->name }}">
        <div class="mt-6 mb-3">
            <span class="text-gray-500">
                {{ \Carbon\Carbon::parse($tutorial['tutorial_date'])->format('j F Y') }}
            </span>
            <a href="{{ route('tutorial.details', ['slug' => $tutorial->slug]) }}">
                <p class="mb-5 text-xl hover:underline underline-offset-2">
                    {{ $tutorial->name }}
                </p>
            </a>
            <a href="{{ route('tutorial.details', ['slug' => $tutorial->slug]) }}"
                class="mt-auto border border-black rounded-full px-6 py-3 text-[--dark] group-hover:bg-[--primary] group-hover:border-[--primary] group-hover:text-white transition w-max group-hover:opacity-100 hover:shadow-lg">
                Learn More
            </a>
        </div>
    </div>
@endforeach
