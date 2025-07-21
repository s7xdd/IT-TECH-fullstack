@extends('layouts.app')

@section('title', 'Brand Listing - '.env('APP_NAME'))

@section('content')

<div class="container mx-auto py-12 px-6 lg:px-12">
    <h1 class="text-4xl font-extrabold text-start text-gray-800 mb-6">{{ $page->getTranslation('title',$lang) }}</h1>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @if (!empty($brands[0]))
            @foreach ($brands as $brand)
                @if ($brand->getTranslation('logo', $lang) !=NULL)
                    <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-4 flex flex-col items-center">
                        <img src="{{ uploaded_asset($brand->getTranslation('logo', $lang)) }}" alt="{{ $brand->getTranslation('name', $lang) }}" class="w-24 h-24 object-contain mb-4">
                    </div>
                @endif
            @endforeach
        @endif
        
    </div>
</div>




@endsection
