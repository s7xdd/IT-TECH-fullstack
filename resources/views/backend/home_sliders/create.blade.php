@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Create Slider</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="{{ route('home-slider.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Name</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Name" value="{{ old('name') }}" id="name"
                                    name="name" class="form-control" required>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">
                                Banner
                            </label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('banner') }}" type="hidden" name="banner" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                @error('banner')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Video Upload</label>
                            <div class="col-md-9">
                                <input type="file" name="video_file" id="video_file" accept="video/*"
                                    class="form-control">
                                @error('video_file')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div id="video_preview" style="margin-top:15px;"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Video URL</label>
                            <div class="col-md-9">
                                <input type="url" name="video_url" class="form-control"
                                    placeholder="https://youtube.com/..." value="{{ old('video_url') }}">
                                @error('video_url')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Custom Video URL</label>
                            <div class="col-md-9">
                                <input type="url" name="video_url" placeholder="https://youtube.com/..."
                                    class="form-control"
                                    value="{{ old('video_url', isset($homeSlider) ? $homeSlider->video_url : '') }}">
                                @error('video_url')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>

                        <div id="banner_form">
                        </div>
                        @error('link')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @error('link_ref_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Sort Order</label>
                            <div class="col-md-9">
                                <input type="number" placeholder="Sort Order" value="{{ old('sort_order') }}"
                                    id="sort_order" name="sort_order" class="form-control" required>
                                @error('sort_order')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Status</label>
                            <div class="col-md-9">
                                <select class="form-control aiz-selectpicker" name="status" id="status" required>
                                    <option {{ old('status') == '1' ? 'selected' : '' }} value="1">Enabled</option>
                                    <option {{ old('status') == '0' ? 'selected' : '' }} value="0">Disabled</option>
                                </select>
                                @error('status')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">
                                Save
                            </button>
                            <a href="{{ route('home-slider.index') }}"
                                class="btn btn-cancel">{{ trans('messages.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            banner_form();
        });

        function banner_form() {
            var link_type = $('#link_type').val();
            var link_error = "{{ $errors->getBag('default')->first('link') }}"
            var link_id_error = "{{ $errors->getBag('default')->first('link_ref_id') }}"
            $.post('{{ route('banners.get_form') }}', {
                _token: '{{ csrf_token() }}',
                link_type: link_type,
            }, function(data) {
                $('#banner_form').html(data);
            });
        }
    </script>

    <script>
        document.getElementById('video_file')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('video_preview');
            if (file && file.type.startsWith('video/')) {
                const url = URL.createObjectURL(file);
                preview.innerHTML =
                    `<video width="320" controls><source src="${url}" type="${file.type}">Your browser does not support video.</video>`;
            } else {
                preview.innerHTML = '';
            }
        });
    </script>
@endsection
