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
            <input type="hidden" name='lang' value="{{ $lang }}">
            <div class="card-header px-0">
                <h6 class="fw-600 mb-0">Page Content</h6>
            </div>
            <div class="card-body px-0">
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Heading 1 </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Heading 1" name="title"
                            value="{{ $page->getTranslation('title', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Heading 2 </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Heading 2" name="title1"
                            value="{{ $page->getTranslation('title1', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Heading 3 </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Heading 3" name="heading2"
                            value="{{ $page->getTranslation('heading2', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Heading 4 </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Heading 4" name="title2"
                            value="{{ $page->getTranslation('title2', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Heading 5</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Heading 5" name="heading3"
                            value="{{ $page->getTranslation('heading3', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Heading 6 </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Heading 6" name="title3"
                            value="{{ $page->getTranslation('title3', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.content') }} <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea class="aiz-text-editor form-control" placeholder="{{ trans('messages.content') }}"
                            data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                            data-min-height="300" name="content" >{!! $page->getTranslation('content', $lang) !!}</textarea>
                    </div>
                </div>
            </div>

            <div class="card-header px-0">
                <h6 class="fw-600 mb-0">Seo Fields</h6>
            </div>
            <div class="card-body px-0">

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.meta_title') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="{{ trans('messages.meta_title') }}"
                            name="meta_title" value="{{ $page->getTranslation('meta_title', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.meta_description') }}</label>
                    <div class="col-sm-10">
                        <textarea rows="5" class="resize-off form-control" placeholder="{{ trans('messages.meta_description') }}"
                            name="meta_description">{!! $page->getTranslation('meta_description', $lang) !!}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.meta_keywords') }}</label>
                    <div class="col-sm-10">
                        <textarea rows="3" class="resize-off form-control" placeholder="{{ trans('messages.meta_keywords') }}"
                            name="keywords">{!! $page->getTranslation('keywords', $lang) !!}</textarea>
                        <small class="text-muted">Separate with coma</small>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.og_title') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="{{ trans('messages.og_title') }}"
                            name="og_title" value="{{ $page->getTranslation('og_title', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.og_description') }}</label>
                    <div class="col-sm-10">
                        <textarea rows="5" class="resize-off form-control" placeholder="{{ trans('messages.og_description') }}"
                            name="og_description">{!! $page->getTranslation('og_description', $lang) !!}</textarea>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.twitter_title') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="{{ trans('messages.twitter_title') }}"
                            name="twitter_title" value="{{ $page->getTranslation('twitter_title', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label"
                        for="name">{{ trans('messages.twitter_description') }}</label>
                    <div class="col-sm-10">
                        <textarea rows="5" class="resize-off form-control" placeholder="{{ trans('messages.twitter_description') }}"
                            name="twitter_description">{!! $page->getTranslation('twitter_description', $lang) !!}</textarea>
                    </div>
                </div>


                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Update Page</button>
                    <a href="{{ route('website.pages') }}" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var lang = '{{ $lang }}';

            if (lang == 'ae') {
                setEditorDirection(true);
            } else {
                setEditorDirection(false);
            }

            function setEditorDirection(isRtl) {
                const editor = $('.aiz-text-editor').next('.note-editor').find('.note-editable');
                editor.attr('dir', isRtl ? 'rtl' : 'ltr'); // Set direction
                editor.css('text-align', isRtl ? 'right' : 'left');
            }
        });
    </script>
@endsection
