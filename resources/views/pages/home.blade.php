@extends('layouts.app')

@section('title', 'Home - ' . env('APP_NAME'))

@section('content')
    <x-home.hero :slider="$slider" :page="$page" :lang="$lang" />

    <x-home.about :page="$page" :lang="$lang" />

    @if (isset($services) && count($services) > 0)
        <x-home.services :services="$services" :page="$page" :lang="$lang" />
    @endif

    @if (isset($tutorials) && count($tutorials) > 0)
        <x-home.tutorials :tutorials="$tutorials" :page="$page" :lang="$lang" />
    @endif

    @if (isset($partners) && count($partners) > 0)
        <x-home.partners :partners="$partners" :page="$page" :lang="$lang" />
    @endif


    @if (isset($blogs) && count($blogs) > 0)
        <x-blogList :blogs="$blogs" :page="$page" :lang="$lang" />
    @endif

    <x-home.careers :page="$page" :lang="$lang" />

    {{-- @if (isset($categories) && count($categories) > 0)
        <x-home.category :categories="$categories" :page="$page" :lang="$lang" />
    @endif

    @if (isset($products) && count($products) > 0)
        <x-home.products-grid :products="$products" :page="$page" :lang="$lang" />
    @else
        <p class="text-center text-gray-600">🚨 No products available at the moment.</p>
    @endif



    <x-home.why-choose-us :page="$page" :lang="$lang" />

    @if (isset($testimonials) && count($testimonials) > 0)
        <x-home.testimonials :testimonials="$testimonials" :page="$page" :lang="$lang" />
    @endif

 --}}


@endsection
