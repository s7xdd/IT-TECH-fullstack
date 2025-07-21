@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">Edit {{ $page->slug }} Page Information</h1>
            </div>
        </div>
    </div>
    <div class="card">


        <form class="p-4" action="{{ route('custom-pages.update', $page->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="type" value="{{ $page->type }}">
            <input  type="hidden" name='lang' value="{{$lang}}">

            <div class="card-header px-0">
                <h6 class="fw-600 mb-0">Page Content</h6>
            </div>
            <div class="card-body px-0">
        
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Title<span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Enter.." name="title" value="{{ $page->getTranslation('title', $lang) }}" required>
                        {{-- <span style="font-size:12px;color: #00b3ff !important;">To highlight specific words, enclose them in square brackets [ ]</span> --}}
                    </div>
                </div>

            </div>

            <div class="card-header px-0">
                <h6 class="fw-600 mb-0">Seo Fields</h6>
            </div>
            <div class="card-body px-0">
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Meta Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Enter.." name="meta_title"
                            value="{{ $page->getTranslation('meta_title', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Meta Description</label>
                    <div class="col-sm-10">
                        <textarea class="resize-off form-control" placeholder="Enter.." name="meta_description" rows="6">{!! $page->getTranslation('meta_description', $lang) !!}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Keywords</label>
                    <div class="col-sm-10">
                        <textarea class="resize-off form-control" placeholder="Enter.." name="keywords" rows="3">{!! $page->getTranslation('keywords', $lang) !!}</textarea>
                        <small class="text-muted">Separate with coma</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">OG Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Enter.." name="og_title" value="{{ $page->getTranslation('og_title', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">OG Description</label>
                    <div class="col-sm-10">
                        <textarea class="resize-off form-control" placeholder="Enter.." name="og_description"  rows="6">{!! $page->getTranslation('og_description', $lang) !!}</textarea>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Twitter Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Enter.." name="twitter_title" value="{{ $page->getTranslation('twitter_title', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Twitter Description</label>
                    <div class="col-sm-10">
                        <textarea class="resize-off form-control" placeholder="Enter.." name="twitter_description" rows="6">{!! $page->getTranslation('twitter_description', $lang) !!}</textarea>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-info">Update Page</button>
                    <a href="{{ route('website.pages') }}" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection
