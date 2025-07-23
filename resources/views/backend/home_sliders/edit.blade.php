@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Update Slider</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="{{ route('home-slider.update', $homeSlider) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Name</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Name" value="{{ old('name', $homeSlider->name) }}"
                                    id="name" name="name" class="form-control" required>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">
                                Banner
                                {{-- <small>(1300x650)</small> --}}
                            </label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('banner', $homeSlider->image) }}" type="hidden" name="banner"
                                        class="selected-files" required>
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

                                @if ($homeSlider->video_file)
                                    <button type="button" id="remove_video_btn" class="btn btn-danger btn-sm mt-2">Remove
                                        Video</button>
                                    <div class="mt-2" id="video_preview_saved">

                                        <video width="320" controls>
                                            <source src="{{ asset('storage/' . $homeSlider->video_file) }}"
                                                type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>

                                    </div>
                                    <input type="hidden" name="remove_video" id="remove_video" value="0">
                                @endif

                                <div id="video_preview" style="margin-top:15px;"></div>
                                @error('video_file')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Video URL</label>
                            <div class="col-md-9">
                                <input type="url" name="video_url" class="form-control"
                                    placeholder="https://youtube.com/..."
                                    value="{{ old('video_url', $homeSlider->video_url) }}">
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
                                <input type="number" placeholder="Sort Order"
                                    value="{{ old('sort_order', $homeSlider->sort_order) }}" id="sort_order"
                                    name="sort_order" class="form-control" required>
                                @error('sort_order')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Status</label>
                            <div class="col-md-9">
                                <select class="form-control aiz-selectpicker" name="status" id="status" required>
                                    <option {{ old('status', $homeSlider->status) == '1' ? 'selected' : '' }}
                                        value="1">
                                        Enabled</option>
                                    <option {{ old('status', $homeSlider->status) == '0' ? 'selected' : '' }}
                                        value="0">
                                        Disabled</option>
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
            var old_data = "{{ $homeSlider->link ?? $homeSlider->link_ref_id }}"
            $.post('{{ route('banners.get_form') }}', {
                _token: '{{ csrf_token() }}',
                link_type: link_type,
                old_data: old_data,
            }, function(data) {
                $('#banner_form').html(data);
            });
        }
    </script>

    <script>
        document.getElementById('video_file')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('video_preview');
            document.getElementById('video_preview_saved')?.setAttribute('style', 'display:none');
            if (file && file.type.startsWith('video/')) {
                const url = URL.createObjectURL(file);
                preview.innerHTML =
                    `<video width="320" controls><source src="${url}" type="${file.type}">Your browser does not support video.</video>`;
            } else {
                preview.innerHTML = '';
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removeBtn = document.getElementById('remove_video_btn');
            const videoPreviewSaved = document.getElementById('video_preview_saved');
            const videoPreview = document.getElementById('video_preview');
            const removeInput = document.getElementById('remove_video');
            const videoFileInput = document.getElementById('video_file');

            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    if (videoPreviewSaved) {
                        videoPreviewSaved.style.display = 'none';
                    }
                    if (removeBtn) {
                        removeBtn.style.display = 'none';
                    }
                    if (removeInput) {
                        removeInput.value = '1';
                    }
                    if (videoFileInput) {
                        videoFileInput.value = '';
                    }
                    if (videoPreview) {
                        videoPreview.innerHTML = '';
                    }
                });
            }

            if (videoFileInput) {
                videoFileInput.addEventListener('change', function(e) {
                    if (videoPreviewSaved) {
                        videoPreviewSaved.style.display = 'none';
                    }
                    if (removeBtn) {
                        removeBtn.style.display = 'none';
                    }
                    if (removeInput) {
                        removeInput.value = '0';
                    }
                    if (this.files.length > 0 && this.files[0].type.startsWith('video/')) {
                        const file = this.files[0];
                        const url = URL.createObjectURL(file);
                        if (videoPreview) {
                            videoPreview.innerHTML =
                                `<video width="320" controls><source src="${url}" type="${file.type}">Your browser does not support video.</video>`;
                        }
                    } else if (videoPreview) {
                        videoPreview.innerHTML = '';
                    }
                });
            }
        });
    </script>
@endsection
