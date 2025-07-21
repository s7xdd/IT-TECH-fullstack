@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">Website Footer</h1>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs nav-fill border-light">
        {{-- @foreach (\App\Models\Language::all() as $key => $language)
            <li class="nav-item">
                <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3"
                    href="{{ route('website.footer', ['lang' => $language->code]) }}">
                    <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}" height="11" class="mr-1">
                    <span>{{ $language->name }}</span>
                </a>
            </li>
        @endforeach --}}
    </ul>


    <div class="card">
        <div class="card-header">
            <h6 class="fw-600 mb-0">Footer Widget</h6>
        </div>
        <div class="card-body">
            <div class="row gutters-10">

                <div class="col-lg-12 mx-auto">
                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">Footer</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                {{-- <div class="form-group">
                                    <label>Moving Text</label>
                                    <input type="hidden" name="types[]" value="footer_moving_text">
                                    <input type="text" class="form-control" placeholder="Enter.." name="footer_moving_text"
                                        value="{{ get_setting('footer_moving_text') }}">
                                </div> --}}

                                <div class="mb-3 mt-2">
                                    <h6 class="mb-0">Newsletter Section</h6>
                                </div>

                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="hidden" name="types[]" value="footer_newsletter_title">
                                    <input type="text" class="form-control" placeholder="Enter.."
                                        name="footer_newsletter_title" value="{{ get_setting('footer_newsletter_title') }}">
                                </div>
                                <div class="form-group">
                                    <label>Subtitle</label>
                                    <input type="hidden" name="types[]" value="footer_newsletter_subtitle">
                                    <input type="text" class="form-control" placeholder="Enter.."
                                        name="footer_newsletter_subtitle"
                                        value="{{ get_setting('footer_newsletter_subtitle') }}">
                                </div>

                                <div class="form-group">
                                    <label>Button Text</label>
                                    <input type="hidden" name="types[][{{ $lang }}]"
                                        value="footer_newsletter_button">
                                    <input type="text" class="form-control" placeholder="Enter.."
                                        name="footer_newsletter_button"
                                        value="{{ get_setting('footer_newsletter_button') }}">
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">About Widget</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label" for="signinSrEmail">Footer Logo</label>
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                        </div>
                                        <div class="form-control file-amount">Choose File</div>
                                        <input type="hidden" name="types[]" value="footer_logo">
                                        <input type="hidden" name="footer_logo" class="selected-files"
                                            value="{{ get_setting('footer_logo') }}">
                                    </div>
                                    <div class="file-preview"></div>
                                </div>
                                {{-- <div class="form-group">
                                    <label>About description</label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="about_us_description">
                                    <textarea class="form-control" name="about_us_description" rows="5" placeholder="Type.." >{!! get_setting('about_us_description', null, $lang) !!}
                                    </textarea>
                                </div> --}}

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mx-auto">
                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">Contact Info Widget</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label>Contact Title</label>
                                    <input type="hidden" name="types[]" value="footer_contact_title">
                                    <input type="text" class="form-control" placeholder="Enter.."
                                        name="footer_contact_title" value="{{ get_setting('footer_contact_title') }}">
                                </div>

                                {{-- <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Phone</label>
                                        <input type="hidden" name="types[]" value="footer_phone">
                                        <input type="text" class="form-control" placeholder="Enter.." name="footer_phone"
                                            value="{{ get_setting('footer_phone') }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <input type="hidden" name="types[]" value="footer_email">
                                        <input type="text" class="form-control" placeholder="Enter.." name="footer_email"
                                            value="{{ get_setting('footer_email') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Address Title</label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="footer_address_title">
                                    <input type="text" class="form-control" placeholder="Enter.." name="footer_address_title"
                                        value="{{ get_setting('footer_address_title') }}">
                                </div> --}}

                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="footer_address">
                                    <textarea class="form-control" placeholder="Enter.." name="footer_address" rows="5">{{ get_setting('footer_address') }}</textarea>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 d-none">
                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">Link Widget One</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Title (Translatable)</label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="widget_one">
                                    <input type="text" class="form-control" placeholder="Widget title"
                                        name="widget_one" value="{{ get_setting('widget_one', null, $lang) }}">
                                </div>
                                <div class="form-group">
                                    <label>Links - (Translatable Label)</label>
                                    <div class="w3-links-target">
                                        <input type="hidden" name="types[][{{ $lang }}]"
                                            value="widget_one_labels">
                                        <input type="hidden" name="types[]" value="widget_one_links">
                                        @if (get_setting('widget_one_labels', null, $lang) != null && get_setting('widget_one_labels', null, $lang) != 'null')
                                            @foreach (json_decode(get_setting('widget_one_labels', null, $lang), true) as $key => $value)
                                                <div class="row gutters-5">
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="Label" name="widget_one_labels[]"
                                                                value="{{ $value }}">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="http://" name="widget_one_links[]"
                                                                value="{{ json_decode(get_setting('widget_one_links'), true)[$key] }}">
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
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-soft-secondary" data-toggle="add-more"
                                        data-content='<div class="row gutters-5">
    										<div class="col-4">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="Label" name="widget_one_labels[]">
    											</div>
    										</div>
    										<div class="col">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="http://" name="widget_one_links[]">
    											</div>
    										</div>
    										<div class="col-auto">
    											<button type="button" class="mt-1 btn btn-icon btn-circle btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
    												<i class="las la-times"></i>
    											</button>
    										</div>
    									</div>'
                                        data-target=".w3-links-target">
                                        Add New
                                    </button>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="col-md-12 ">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Footer Menu Setting</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">Shop by Category Title</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="hidden" name="types[]" value="footer_category_title_1">
                                    <input type="text" class="form-control" placeholder="Shop by Category Title"
                                        name="footer_category_title_1"
                                        value="{{ get_setting('footer_category_title_1') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label>Categories (Max 6)</label>
                        <div class="new_collection-categories-target">
                            <input type="hidden" name="types[]" value="footer_categories">
                            <input type="hidden" name="page_type" value="new_collection">

                            @if (get_setting('footer_categories') != null && get_setting('footer_categories') != 'null')
                                @foreach (json_decode(get_setting('footer_categories'), true) as $key => $value)
                                    <div class="row gutters-5">
                                        <div class="col">
                                            <div class="form-group">
                                                <select class="form-control aiz-selectpicker" name="footer_categories[]"
                                                    data-live-search="true" data-selected={{ $value }} required>
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
                                            <button type="button" class="mt-1 btn btn-icon btn-circle btn-soft-danger"
                                                data-toggle="remove-parent" data-parent=".row">
                                                <i class="las la-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-soft-secondary" data-toggle="add-more"
                            data-content='<div class="row gutters-5">
							<div class="col">
								<div class="form-group">
									<select class="form-control aiz-selectpicker" name="footer_categories[]" data-live-search="true" required>
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
                        </button>
                    </div>


                    <div class="">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">Shop by Service Title</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="hidden" name="types[]" value="footer_category_title_2">
                                    <input type="text" class="form-control" placeholder="Enter"
                                        name="footer_category_title_2"
                                        value="{{ get_setting('footer_category_title_2') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Services (Max 6)</label>
                        <div class="home-brands-target">
                            <input type="hidden" name="types[]" value="footer_services">
                            <input type="hidden" name="page_type" value="footer_services">

                            @if (get_setting('footer_services') != null && get_setting('footer_services') != 'null')
                                @foreach (json_decode(get_setting('footer_services'), true) as $key => $value)
                                    <div class="row gutters-5">
                                        <div class="col">
                                            <div class="form-group">
                                                <select class="form-control aiz-selectpicker" name="footer_services[]"
                                                    data-live-search="true" title="Select "
                                                    data-selected={{ $value }} required>

                                                    @foreach ($services as $serv)
                                                        <option value="{{ $serv->id }}">{{ $serv->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="mt-1 btn btn-icon btn-circle btn-soft-danger"
                                                data-toggle="remove-parent" data-parent=".row">
                                                <i class="las la-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-soft-secondary" data-toggle="add-more"
                            data-content='<div class="row gutters-5">
							<div class="col">
								<div class="form-group">
									<select class="form-control aiz-selectpicker" name="footer_services[]" data-live-search="true"  title="Select " required>

										@foreach ($services as $key => $serv)
<option value="{{ $serv->id }}">{{ $serv->name }}</option>
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
                            data-target=".home-brands-target">
                            Add New
                        </button>
                    </div>

                    <div class="">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">Resources Title</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="hidden" name="types[]" value="footer_category_title_3">
                                    <input type="text" class="form-control" placeholder="Enter.."
                                        name="footer_category_title_3"
                                        value="{{ get_setting('footer_category_title_3') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">Company Title</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="hidden" name="types[]" value="footer_category_title_4">
                                    <input type="text" class="form-control" placeholder="Enter.."
                                        name="footer_category_title_4"
                                        value="{{ get_setting('footer_category_title_4') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    {{-- <div class="card">
        <div class="card-header">
            <h6 class="fw-600 mb-0">Footer Bottom</h6>
        </div>
        <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="card shadow-none bg-light">
                    <div class="card-header">
                        <h6 class="mb-0">Copyright Widget </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Copyright Text</label>
                            <input type="hidden" name="types[][{{ $lang }}]" value="frontend_copyright_text">
                            <textarea class="form-control" name="frontend_copyright_text" rows="3">{!! get_setting('frontend_copyright_text', null, $lang) !!}
                            </textarea>
                        </div>
                    </div>
                </div>



                <div class="card shadow-none bg-light d-none">
                    <div class="card-header">
                        <h6 class="mb-0">Social Link Widget </h6>
                    </div>
                    <div class="card-body">


                        <div class="form-group">
                            <label>Social Links Title</label>
                            <div class="input-group form-group">
                                <input type="hidden" name="types[]" value="social_title">
                                <input type="text" class="form-control" placeholder="" name="social_title"
                                    value="{{ get_setting('social_title') }}">

                            </div>
                        </div>

                        <div class="form-group">
                            <label>Social Links</label>
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-facebook-f"></i></span>
                                </div>
                                <input type="hidden" name="types[]" value="facebook_link">
                                <input type="text" class="form-control" placeholder="http://" name="facebook_link"
                                    value="{{ get_setting('facebook_link') }}">
                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-twitter"></i></span>
                                </div>
                                <input type="hidden" name="types[]" value="twitter_link">
                                <input type="text" class="form-control" placeholder="http://" name="twitter_link"
                                    value="{{ get_setting('twitter_link') }}">
                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-instagram"></i></span>
                                </div>
                                <input type="hidden" name="types[]" value="instagram_link">
                                <input type="text" class="form-control" placeholder="http://" name="instagram_link"
                                    value="{{ get_setting('instagram_link') }}">
                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-youtube"></i></span>
                                </div>
                                <input type="hidden" name="types[]" value="youtube_link">
                                <input type="text" class="form-control" placeholder="http://" name="youtube_link"
                                    value="{{ get_setting('youtube_link') }}">
                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-linkedin-in"></i></span>
                                </div>
                                <input type="hidden" name="types[]" value="linkedin_link">
                                <input type="text" class="form-control" placeholder="http://" name="linkedin_link"
                                    value="{{ get_setting('linkedin_link') }}">
                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-whatsapp"></i></span>
                                </div>
                                <input type="hidden" name="types[]" value="whatsapp_link">
                                <input type="text" class="form-control" placeholder="http://" name="whatsapp_link"
                                    value="{{ get_setting('whatsapp_link') }}">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div> --}}
@endsection


@section('header')
    <style>
        .file-preview-item .thumb {
            -ms-flex: 0 0 50px;
            flex: 0 0 150px;
            max-width: 150px;
            height: 145px;
            width: 150px;
        }
    </style>
@endsection
