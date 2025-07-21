@extends('layouts.app')

@section('title', 'Return Policy - '.env('APP_NAME'))

@section('content')

<div class="container mx-auto py-12 px-6 lg:px-12">
        <div class=" mx-auto">
            <h1 class="text-3xl font-bold mb-4">{{ $page->getTranslation('title', $lang) }}</h1>
            
            {!! $page->getTranslation('content', $lang) !!}
        </div>

@endsection
