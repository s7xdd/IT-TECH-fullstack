@extends('backend.layouts.app')

@section('content')
    <div class="row">
        {{-- <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Free Shipping Settings</h5>
                </div>
                <form action="{{ route('shipping_configuration.free_shipping') }}" method="POST"
                    enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf
                        <input type="hidden" name="type" value="free_shipping">

                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">
                                Default shipping amount
                            </label>
                            <div class="col-md-8">
                                <input step="0.01" class="form-control" type="number" name="default_shipping_amount"
                                    value="{{ get_setting('default_shipping_amount') ?? 0 }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">
                                Free shipping status
                            </label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input name="free_shipping_status"
                                        {{ get_setting('free_shipping_status') == '1' ? 'checked' : '' }} type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">
                                Free shipping min amount
                            </label>
                            <div class="col-md-8">
                                <input step="0.01" class="form-control" type="number" name="free_shipping_min_amount"
                                    value="{{ get_setting('free_shipping_min_amount') ?? 0 }}">
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}
        {{-- <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Order Return Time Limit</h5>
                </div>
                <form action="{{ route('configuration.return_settings') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf
                        <input type="hidden" name="type" value="return_product_limit">

                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">
                                Return Time Limit (Days)
                            </label>
                            <div class="col-md-8">
                                <input step="1" class="form-control" type="number" name="default_return_time"
                                    value="{{ get_setting('default_return_time') ?? 0 }}">
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}

        {{-- <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">WhatsApp Contact Number for Services</h5>
                </div>
                <form action="{{ route('configuration.service_settings') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf

                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">
                                WhatsApp Number
                            </label>
                            <div class="col-md-8">
                                <input class="form-control" type="tel" id="default_service_whatsapp"
                                    name="default_service_whatsapp" required
                                    value="{{ get_setting('default_service_whatsapp') ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}

        @php
            $value = json_decode(get_setting('why_choose_us'), true) ?? [];
        @endphp

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

                        <div class="mt-10">
                            @include('components.inputs.custom-fields', [
                                'custom_fields' => $value,
                                'fields' => [
                                    ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
                                    ['name' => 'subtitle', 'type' => 'text', 'label' => 'Subtitle'],
                                    ['name' => 'image', 'type' => 'image', 'label' => 'Slider Image'],
                                ],
                                'field_name' => 'why_choose_us',
                                'label' => 'Slider Content',
                            ])
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
