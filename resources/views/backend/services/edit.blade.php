@extends('backend.layouts.app')

@section('content')
    <style>
        .remove-attachment {
            display: none;
        }
    </style>

    @php
        $custom_fields = old(
            'custom_fields',
            $service->custom_fields ? json_decode($service->custom_fields, true) : [],
        );
    @endphp

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h4">{{ trans('messages.service') . ' ' . trans('messages.information') }}</h5>
    </div>

    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-body p-0">
                {{-- <ul class="nav nav-tabs nav-fill border-light">
                    @foreach (\App\Models\Language::all() as $key => $language)
                        <li class="nav-item">
                            <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3"
                                href="{{ route('service.edit', ['id' => $service->id, 'lang' => $language->code]) }}">
            <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}" height="11"
                class="mr-1">
            <span>{{ $language->name }}</span>
            </a>
            </li>
            @endforeach
            </ul> --}}
                <form class="p-4" action="{{ route('service.update', $service->id) }}" method="POST"
                    enctype="multipart/form-data">
                    <input type="hidden" name="lang" value="{{ $lang }}">
                    @csrf

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ trans('messages.name') }} <i
                                class="las la-language text-danger"
                                title="{{ trans('messages.translatable') }}"></i></label>
                        <div class="col-md-9">
                            <input type="text" name="name" value="{{ $service->getTranslation('name', $lang) }}"
                                class="form-control" id="name" onchange="title_update(this)"
                                placeholder="{{ trans('messages.name') }}">
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ trans('messages.parent_service') }}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control" name="parent_id">
                                <option value="">{{ trans('messages.none') }}</option>
                                @foreach ($all_services as $parent)
                                    @if ($parent->id !== $service->id)
                                        <option value="{{ $parent->id }}"
                                            {{ $service->parent_id == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->getTranslation('name') }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ trans('messages.slug') }}<span
                                class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{ trans('messages.slug') }}" id="slug" name="slug"
                                class="form-control" value="{{ $service->slug }}">
                            @error('slug')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ trans('messages.price') }} </label>
                    <div class="col-md-6">
                        <input type="number" lang="en" min="0" value="{{ $service->price ?? 0 }}" step="0.01" placeholder="{{ trans('messages.price') }}" name="price" class="form-control">
                    </div>
                </div> --}}

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{ trans('messages.image') }}</label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ trans('messages.browse') }}
                                    </div>
                                </div>
                                <div class="form-control file-amount">{{ trans('messages.choose_file') }}</div>
                                <input type="hidden" name="image" value="{{ $service->image }}" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">Icon</label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ trans('messages.browse') }}
                                    </div>
                                </div>
                                <div class="form-control file-amount">{{ trans('messages.choose_file') }}</div>
                                <input type="hidden" name="icon" value="{{ $service->icon }}" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Short Description <i class="las la-language text-danger"
                                title="{{ trans('messages.translatable') }}"></i></label>
                        <div class="col-md-9">
                            <input type="text" name="short_description"
                                value="{{ $service->getTranslation('short_description', $lang) }}" class="form-control"
                                id="name" placeholder="Short Description">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{ trans('messages.description') }}</label>
                        <div class="col-md-9">
                            <textarea class="aiz-text-editor" data-min-height="300" name="description">{{ old('description', $service->getTranslation('description', $lang)) }}</textarea>
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <label class="col-md-3 col-form-label">Features (Separate by comma)</label>
                        <div class="col-md-9">
                            <textarea rows="3" name="features" class="form-control" placeholder="Features">{{ old('features', $service->getTranslation('features', $lang)) }}</textarea>
                        </div>
                    </div> --}}


                    @include('components.inputs.custom-fields', [
                        'custom_fields' => $custom_fields,
                        'field_name' => 'custom_fields',
                        'fields' => ['title', 'content'],
                        'label' => 'Custom Fields (Max 6)',
                    ])

                    @if ($lang == 'en')
                        <div class="form-group  row">
                            <label class="col-md-3 col-form-label">{{ trans('messages.active_status') }}</label>
                            <div class="col-md-9">
                                <select class="select2 form-control" name="status">
                                    <option {{ old('status', $service->status) == 1 ? 'selected' : '' }} value="1">
                                        {{ trans('messages.yes') }}
                                    </option>
                                    <option {{ old('status', $service->status) == 0 ? 'selected' : '' }} value="0">
                                        {{ trans('messages.no') }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Sort Order</label>
                            <div class="col-md-9">
                                <input type="number" name="sort_order" class="form-control"
                                    value="{{ old('sort_order', $service->sort_order) }}">
                                @error('sort_order')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif



                    <h5 class="mb-0 h6">{{ trans('messages.seo_section') }}</h5>
                    <hr>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">{{ trans('messages.meta_title') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_title"
                                placeholder="{{ trans('messages.meta_title') }}"
                                value="{{ old('meta_title', $service->getTranslation('meta_title', $lang)) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">{{ trans('messages.meta_description') }}</label>
                        <div class="col-md-9">
                            <textarea name="meta_description" rows="5" class="form-control">{{ old('meta_description', $service->getTranslation('meta_description', $lang)) }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">{{ trans('messages.meta_keywords') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_keywords"
                                placeholder="{{ trans('messages.meta_keywords') }}"
                                value="{{ old('meta_keywords', $service->getTranslation('meta_keywords', $lang)) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">{{ trans('messages.og_title') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="og_title"
                                placeholder="{{ trans('messages.og_title') }}"
                                value="{{ old('og_title', $service->getTranslation('og_title', $lang)) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">{{ trans('messages.og_description') }}</label>
                        <div class="col-md-9">
                            <textarea name="og_description" rows="5" class="form-control">{{ old('og_description', $service->getTranslation('og_description', $lang)) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">{{ trans('messages.twitter_title') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="twitter_title"
                                placeholder="{{ trans('messages.twitter_title') }}"
                                value="{{ old('twitter_title', $service->getTranslation('twitter_title', $lang)) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">{{ trans('messages.twitter_description') }}</label>
                        <div class="col-md-9">
                            <textarea name="twitter_description" rows="5" class="form-control">{{ old('twitter_description', $service->getTranslation('twitter_description', $lang)) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ trans('messages.Save') }}</button>
                        <a href="{{ route('service.index') }}" class="btn btn-cancel">{{ trans('messages.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function title_update(e) {
            var title = e.value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('generate-slug') }}",
                type: 'GET',
                data: {
                    title: title
                },
                success: function(response) {
                    $('#slug').val(response);
                }
            });
        }
    </script>
@endsection
