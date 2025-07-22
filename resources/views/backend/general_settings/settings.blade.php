@extends('backend.layouts.app')

@section('content')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Why Choose Us</h5>
                </div>
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body py-3">
                        @csrf
                        <input type="hidden" name="types[]" value="why_choose_us">
                        <input type="hidden" name="types[]" value="why_choose_us_title">
                        <input type="hidden" name="types[]" value="why_choose_us_subtitle">

                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">Title</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="why_choose_us_title"
                                    value="{{ get_setting('why_choose_us_title') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">Subtitle</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="why_choose_us_subtitle"
                                    value="{{ get_setting('why_choose_us_subtitle') }}">
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right mt-3">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Why Choose Us Slider Content</h5>
                </div>
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body py-3">
                        @csrf
                        <input type="hidden" name="types[]" value="why_choose_us">
                        <input type="hidden" name="types[]" value="why_choose_us_title">
                        <input type="hidden" name="types[]" value="why_choose_us_subtitle">

                        @for ($i = 1; $i <= 6; $i++)
                            <input type="hidden" name="types[]" value="why_choose_us_title{{ $i }}">
                            <input type="hidden" name="types[]" value="why_choose_us_subtitle{{ $i }}">
                            <input type="hidden" name="types[]" value="why_choose_us_image{{ $i }}">

                            <div class="form-group row">
                                <label class="col-md-4 col-from-label">Title {{ $i }}</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" name="why_choose_us_title{{ $i }}"
                                        value="{{ get_setting('why_choose_us_title' . $i) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-from-label">Subtitle {{ $i }}</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text"
                                        name="why_choose_us_subtitle{{ $i }}"
                                        value="{{ get_setting('why_choose_us_subtitle' . $i) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="signinSrEmail">Image
                                    {{ $i }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ trans('messages.browse') }}
                                            </div>
                                        </div>
                                        <div class="form-control file-amount">{{ trans('messages.choose_file') }}</div>
                                        <input type="hidden" name="why_choose_us_image{{ $i }}"
                                            value="{{ get_setting('why_choose_us_image' . $i) }}" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>

                            <hr>
                        @endfor

                        <div class="form-group mb-0 text-right mt-3">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



    </div>
@endsection


@section('script')
    <script type="text/javascript">
        const phoneInput = document.getElementById('default_service_whatsapp');

        phoneInput.addEventListener('input', () => {
            phoneInput.value = phoneInput.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
        });
    </script>
@endsection
