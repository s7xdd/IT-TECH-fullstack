@extends('backend.layouts.app')
@section('content')
    <div class="row">
        <div class="col-xl-10 mx-auto">
            <h4 class="fw-600">Home Page Settings</h4>

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
                <div class="card-header">
                    <h5 class="mb-0">Hero Section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Title<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="title"
                                    value="{{ old('title', $page->getTranslation('title', $lang)) }}" required>
                                <span style="font-size:12px;color: #00b3ff !important;">To highlight specific words, enclose
                                    them in square brackets [ ]</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Subtitle <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="sub_title"
                                    value="{{ old('sub_title', $page->getTranslation('sub_title', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Button 1 Text<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="heading1"
                                    value="{{ old('heading1', $page->getTranslation('heading1', $lang)) }}" required>
                                <span style="font-size:12px;color: #00b3ff !important;">To highlight specific words, enclose
                                    them in square brackets [ ]</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Button 2 Text <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="heading2"
                                    value="{{ old('heading2', $page->getTranslation('heading2', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            {{-- <label>Categories</label> --}}
                            <div class="new_collection-categories-target">
                                <input type="hidden" name="types[]" value="home_categories">
                                <input type="hidden" name="page_id" value="{{ $page_id }}">
                                <input type="hidden" name="lang" value="{{ $lang }}">

                                {{-- @if (get_setting('home_categories') != null && get_setting('home_categories') != 'null')
                                    @foreach (json_decode(get_setting('home_categories'), true) as $key => $value)
                                        <div class="row gutters-5">
                                            <div class="col">
                                                <div class="form-group">
                                                    <select class="form-control aiz-selectpicker" name="home_categories[]" data-live-search="true" data-selected={{ $value }}
                                                        required>
                                                        <option value="">Select Category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}
                                                            </option>
                                                            @foreach ($category->childrenCategories as $childCategory)
                                                                @include('backend.categories.child_category', [
                                                                    'child_category' => $childCategory,
                                                                ])
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button"
                                                    class="mt-1 btn btn-icon btn-circle btn-soft-danger"
                                                    data-toggle="remove-parent" data-parent=".row">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif --}}
                            </div>
                            {{-- <button type="button" class="btn btn-soft-secondary" data-toggle="add-more"
                                data-content='<div class="row gutters-5">
								<div class="col">
									<div class="form-group">
										<select class="form-control aiz-selectpicker" name="home_categories[]" data-live-search="true" required>
                                            <option value="">Select Category</option>
											@foreach ($categories as $key => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @foreach ($category->childrenCategories as $childCategory)
                                            @include('backend.categories.child_category', [
                                                'child_category' => $childCategory,
                                            ])
                                            @endforeach
                                            @endforeach
										</select>
									</div>
								</div>
								<div class="col-auto">
									<button type="button" class="mt-1 btn btn-icon btn-circle btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
										<i class="las la-times"></i>
									</button>
								</div>
							</div>'
                                data-target=".new_collection-categories-target">
                                Add New
                            </button> --}}
                        </div>



                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">About section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Title<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="heading5"
                                    value="{{ old('heading5', $page->getTranslation('heading5', $lang)) }}" required>
                                <span style="font-size:12px;color: #00b3ff !important;">To highlight specific words,
                                    enclose them in square brackets [ ]</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Subtitle <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="heading6"
                                    value="{{ old('heading6', $page->getTranslation('heading6', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Button Title<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="title1"
                                    value="{{ old('title1', $page->getTranslation('title1', $lang)) }}" required>
                            </div>
                        </div>

                        {{-- @for ($i = 1; $i <= 6; $i++)
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="signinSrEmail">Image
                                    {{ $i }}</label>
                                <div class="col-md-10">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ trans('messages.browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ trans('messages.choose_file') }}</div>
                                        <input type="hidden" name="image{{ $i }}"
                                            value="{{ $page->{'image' . $i} ?? '' }}" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                        @endfor --}}

                        <hr>
                        {{-- <div class="form-group row">
                            <h5 class="mb-0 ml-3">Box 1 Content</h5>
                        </div> --}}

                        {{-- <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Content <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="content1" placeholder="Enter.." rows="3">{!! $page->getTranslation('content1', $lang) !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">Icon </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ $page->image9 }}" type="hidden" name="image9"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">Icon </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ $page->image7 }}" type="hidden" name="image7"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row">
                            <h5 class="mb-0 ml-3">Box 3 Content</h5>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Title<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="title3"
                                    value="{{ old('title3', $page->getTranslation('title3', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Content <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="content3" placeholder="Enter.." rows="3">{!! $page->getTranslation('content3', $lang) !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">Icon </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ $page->image8 }}" type="hidden" name="image8"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row">
                            <h5 class="mb-0 ml-3">Box 4 Content</h5>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Title<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="heading7"
                                    value="{{ old('heading7', $page->getTranslation('heading7', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Content <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="content4" placeholder="Enter.." rows="3">{!! $page->getTranslation('content4', $lang) !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">Icon </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ $page->image }}" type="hidden" name="image10"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div> --}}

                        <div class="text-right">
                            <input type="hidden" name="page_type" value="highlights_section">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Services</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Title <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="heading3"
                                    value="{{ old('heading3', $page->getTranslation('heading3', $lang)) }}" required>
                                <span style="font-size:12px;color: #00b3ff !important;">To highlight specific words,
                                    enclose them in square brackets [ ]</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Subtitle <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="heading4"
                                    value="{{ old('heading4', $page->getTranslation('heading4', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Subtitle 2<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="heading8"
                                    value="{{ old('heading8', $page->getTranslation('heading8', $lang)) }}" required>
                                <span style="font-size:12px;color: #00b3ff !important;">To highlight specific words,
                                    enclose them in square brackets [ ]</span>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label class="col-md-2 col-from-label">Services (Max 6)</label>
                            <div class="col-md-10">
                                <input type="hidden" name="types[]" value="home_services">
                                <input type="hidden" name="page_type" value="home_services">
                                <select name="home_services[]" class="form-control aiz-selectpicker" multiple
                                    data-max-options="6" data-live-search="true" title="Select Services"
                                    data-selected="{{ get_setting('home_services') }}">
                                    @foreach ($services as $serv)
                                        <option value="{{ $serv->id }}">{{ $serv->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tutorials</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Title<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="title2"
                                    value="{{ old('title2', $page->getTranslation('title2', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Content <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="content2" placeholder="Enter.." rows="3">{!! $page->getTranslation('content2', $lang) !!}</textarea>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Explore Products</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Title<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="heading1" value="{{ old('heading1', $page->getTranslation('heading1', $lang)) }}" required>
                                <span style="font-size:12px;color: #00b3ff !important;">To highlight specific words, enclose them in square brackets [ ]</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Subtitle <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="heading2" value="{{ old('heading2', $page->getTranslation('heading2', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-from-label">{{ trans('messages.products') }} (10 Numbers)</label>
                            <div class="col-md-10">
                                <input type="hidden" name="types[]" value="home_products">
                                <input type="hidden" name="page_type" value="home_products">
                                <select name="home_products[]" class="form-control aiz-selectpicker" multiple
                                    data-max-options="10" data-actions-box="true" data-live-search="true"
                                    title="Select Products" data-selected="{{ get_setting('home_products') }}">
                                    @foreach ($products as $key => $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div> --}}

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Blogs Section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Title<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="heading9"
                                    value="{{ old('heading9', $page->getTranslation('heading9', $lang)) }}" required>
                                <span style="font-size:12px;color: #00b3ff !important;">To highlight specific words,
                                    enclose them in square brackets [ ]</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Subtitle<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="content5"
                                    value="{{ old('content5', $page->getTranslation('content5', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Career Section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">Subtitle<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter.." name="content"
                                    value="{{ old('content', $page->getTranslation('content', $lang)) }}" required>
                                <span style="font-size:12px;color: #00b3ff !important;">To highlight specific words,
                                    enclose them in square brackets [ ]</span>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">


                <form class="p-4" action="{{ route('business_settings.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="page_id" value="{{ $page_id }}">
                    <input type="hidden" name="lang" value="{{ $lang }}">
                    <div class="card-header px-0">
                        <h6 class="fw-600 mb-0">Seo Fields</h6>
                    </div>
                    <div class="card-body px-0">

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label"
                                for="name">{{ trans('messages.meta_title') }}</label>
                            <div class="col-sm-10">
                                <input type="text" @if ($lang == 'ae') dir="rtl" @endif
                                    class="form-control" placeholder="{{ trans('messages.meta_title') }}"
                                    name="meta_title" value="{{ $page->getTranslation('meta_title', $lang) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label"
                                for="name">{{ trans('messages.meta_description') }}</label>
                            <div class="col-sm-10">
                                <textarea class="resize-off form-control" placeholder="{{ trans('messages.meta_description') }}"
                                    name="meta_description" @if ($lang == 'ae') dir="rtl" @endif>{!! $page->getTranslation('meta_description', $lang) !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label"
                                for="name">{{ trans('messages.meta_keywords') }}</label>
                            <div class="col-sm-10">
                                <textarea @if ($lang == 'ae') dir="rtl" @endif class="resize-off form-control"
                                    placeholder="{{ trans('messages.meta_keywords') }}" name="keywords">{!! $page->getTranslation('keywords', $lang) !!}</textarea>
                                <small class="text-muted">Separate with coma</small>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label"
                                for="name">{{ trans('messages.og_title') }}</label>
                            <div class="col-sm-10">
                                <input type="text" @if ($lang == 'ae') dir="rtl" @endif
                                    class="form-control" placeholder="{{ trans('messages.og_title') }}" name="og_title"
                                    value="{{ $page->getTranslation('og_title', $lang) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label"
                                for="name">{{ trans('messages.og_description') }}</label>
                            <div class="col-sm-10">
                                <textarea class="resize-off form-control" placeholder="{{ trans('messages.og_description') }}" name="og_description"
                                    @if ($lang == 'ae') dir="rtl" @endif>{!! $page->getTranslation('og_description', $lang) !!}</textarea>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label"
                                for="name">{{ trans('messages.twitter_title') }}</label>
                            <div class="col-sm-10">
                                <input type="text" @if ($lang == 'ae') dir="rtl" @endif
                                    class="form-control" placeholder="{{ trans('messages.twitter_title') }}"
                                    name="twitter_title" value="{{ $page->getTranslation('twitter_title', $lang) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label"
                                for="name">{{ trans('messages.twitter_description') }}</label>
                            <div class="col-sm-10">
                                <textarea class="resize-off form-control" placeholder="{{ trans('messages.twitter_description') }}"
                                    name="twitter_description" @if ($lang == 'ae') dir="rtl" @endif>{!! $page->getTranslation('twitter_description', $lang) !!}</textarea>
                            </div>
                        </div>



                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                            <a href="{{ route('website.pages') }}" class="btn btn-cancel">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            AIZ.plugins.bootstrapSelect('refresh');

            $('.aiz-selectpicker').on('shown.bs.select', function() {
                var select = $(this);
                var selectedOptions = select.find('option:selected').detach();
                select.prepend(selectedOptions);
                select.selectpicker('refresh');
            });
        });

        $('.remove-galley').on('click', function() {
            thumbnail = $(this)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{ route('page.delete_image') }}',
                data: {
                    url: $(thumbnail).data('url'),
                    id: '{{ $page->id }}'
                },
                success: function(data) {
                    if (data == 1) {
                        $(thumbnail).closest('.file-preview-item').remove();
                        AIZ.plugins.notify('success',
                            "{{ trans('messages.image') . trans('messages.deleted_msg') }}");
                    } else {
                        AIZ.plugins.notify('danger', "{{ trans('messages.something_went_wrong') }}");
                    }

                }
            });
        });
    </script>
@endsection
