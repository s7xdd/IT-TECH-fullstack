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
        {{-- <ul class="nav nav-tabs nav-fill border-light">
            @foreach (\App\Models\Language::all() as $key => $language)
                <li class="nav-item">
                    <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('custom-pages.edit', ['id'=>$page->type, 'lang'=> $language->code] ) }}">
                        <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                        <span>{{$language->name}}</span>
                    </a>
                </li>
            @endforeach
        </ul> --}}

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
                    <label class="col-sm-2 col-from-label" for="name">Title <span class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Enter.." name="title"
                            value="{{ $page->getTranslation('title', $lang) }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <h5 class="mb-0 ml-3">Who We Are Section</h5>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.content') }} <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea class="aiz-text-editor form-control" placeholder="{{ trans('messages.content') }}"
                            data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                            data-min-height="300" name="content" required>{!! $page->getTranslation('content', $lang) !!}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="signinSrEmail">
                     {{ trans('messages.image') }}
                    </label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">
                                    Browse
                                </div>
                            </div>
                            <div class="form-control file-amount">Choose File</div>
                            <input value="{{ old('image', $page->image) }}" type="hidden" name="image"
                                class="selected-files" required>
                        </div>
                        <div class="file-preview box sm">
                        </div>
                        @error('image')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <h5 class="mb-0 ml-3">Our Mission Section</h5>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.heading') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Enter.." name="heading2"
                            value="{{ $page->getTranslation('heading2', $lang) }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.content') }} <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea class=" form-control" placeholder="Enter.." name="content1" rows="5" required>{!! $page->getTranslation('content1', $lang) !!}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <h5 class="mb-0 ml-3">About Us Section</h5>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.heading') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Enter.." name="heading4"
                            value="{{ $page->getTranslation('heading4', $lang) }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.content') }} <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="content2" placeholder="{{ trans('messages.content') }}" rows="5">{!! $page->getTranslation('content2', $lang) !!}</textarea>
                    </div>
                </div>

                <hr>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Title 1 <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Enter.." name="heading5"
                            value="{{ old('heading5', $page->getTranslation('heading5', $lang)) }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="signinSrEmail">
                        Icon 1
                    </label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">
                                    Browse
                                </div>
                            </div>
                            <div class="form-control file-amount">Choose File</div>
                            <input value="{{ old('image1', $page->image1) }}" type="hidden" name="image1"
                                class="selected-files" required>
                        </div>
                        <div class="file-preview box sm">
                        </div>
                        @error('image1')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Content 1<span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="content3" placeholder="Enter.." rows="5">{!! $page->getTranslation('content3', $lang) !!}</textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Title 2 <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Enter.." name="heading6"
                            value="{{ old('heading6', $page->getTranslation('heading6', $lang)) }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="signinSrEmail">
                        Icon 2
                    </label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">
                                    Browse
                                </div>
                            </div>
                            <div class="form-control file-amount">Choose File</div>
                            <input value="{{ old('image2', $page->image2) }}" type="hidden" name="image2"
                                class="selected-files" required>
                        </div>
                        <div class="file-preview box sm">
                        </div>
                        @error('image2')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Content 2<span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="content4" placeholder="Enter.." rows="5">{!! $page->getTranslation('content4', $lang) !!}</textarea>
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Title 3<span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Enter.." name="heading7"
                            value="{{ old('heading7', $page->getTranslation('heading7', $lang)) }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="signinSrEmail">
                        Icon 3
                    </label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">
                                    Browse
                                </div>
                            </div>
                            <div class="form-control file-amount">Choose File</div>
                            <input value="{{ old('image3', $page->image3) }}" type="hidden" name="image3"
                                class="selected-files" required>
                        </div>
                        <div class="file-preview box sm">
                        </div>
                        @error('image3')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Content 3<span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="content5" placeholder="Enter.." rows="5">{!! $page->getTranslation('content5', $lang) !!}</textarea>
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Title 4<span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Enter.." name="heading8"
                            value="{{ old('heading8', $page->getTranslation('heading8', $lang)) }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="signinSrEmail">
                        Icon 4
                    </label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">
                                    Browse
                                </div>
                            </div>
                            <div class="form-control file-amount">Choose File</div>
                            <input value="{{ old('image4', $page->image4) }}" type="hidden" name="image4"
                                class="selected-files" required>
                        </div>
                        <div class="file-preview box sm">
                        </div>
                        @error('image4')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">Content 4<span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="content6" placeholder="Enter.." rows="5">{!! $page->getTranslation('content6', $lang) !!}</textarea>
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
                        <input type="text" @if ($lang == 'ae') dir="rtl" @endif class="form-control"
                            placeholder="{{ trans('messages.meta_title') }}" name="meta_title"
                            value="{{ $page->getTranslation('meta_title', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label"
                        for="name">{{ trans('messages.meta_description') }}</label>
                    <div class="col-sm-10">
                        <textarea @if ($lang == 'ae') dir="rtl" @endif class="resize-off form-control"
                            placeholder="{{ trans('messages.meta_description') }}" rows="5" name="meta_description">{!! $page->getTranslation('meta_description', $lang) !!}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.meta_keywords') }}</label>
                    <div class="col-sm-10">
                        <textarea @if ($lang == 'ae') dir="rtl" @endif class="resize-off form-control"
                            placeholder="{{ trans('messages.meta_keywords') }}" rows="3" name="keywords">{!! $page->getTranslation('keywords', $lang) !!}</textarea>
                        <small class="text-muted">Separate with coma</small>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.og_title') }}</label>
                    <div class="col-sm-10">
                        <input type="text" @if ($lang == 'ae') dir="rtl" @endif class="form-control"
                            placeholder="{{ trans('messages.og_title') }}" name="og_title"
                            value="{{ $page->getTranslation('og_title', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.og_description') }}</label>
                    <div class="col-sm-10">
                        <textarea @if ($lang == 'ae') dir="rtl" @endif class="resize-off form-control"
                            placeholder="{{ trans('messages.og_description') }}" rows="5" name="og_description">{!! $page->getTranslation('og_description', $lang) !!}</textarea>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.twitter_title') }}</label>
                    <div class="col-sm-10">
                        <input type="text" @if ($lang == 'ae') dir="rtl" @endif class="form-control"
                            placeholder="{{ trans('messages.twitter_title') }}" name="twitter_title"
                            value="{{ $page->getTranslation('twitter_title', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label"
                        for="name">{{ trans('messages.twitter_description') }}</label>
                    <div class="col-sm-10">
                        <textarea @if ($lang == 'ae') dir="rtl" rows="5" @endif class="resize-off form-control"
                            placeholder="{{ trans('messages.twitter_description') }}" name="twitter_description">{!! $page->getTranslation('twitter_description', $lang) !!}</textarea>
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

    <script>
        document.querySelector('input[name="image"]').addEventListener('change', function(event) {
            const fileInput = event.target;
            const previewBox = fileInput.closest('.form-group').querySelector('.file-preview');
            const files = fileInput.files;

            if (files && files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewBox.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mt-2 file-preview-item">
                        <div class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                            <img src="${e.target.result}" class="img-fit">
                        </div>
                    </div>
                `;
                }
                reader.readAsDataURL(files[0]);
            }
        });
    </script>
@endsection
