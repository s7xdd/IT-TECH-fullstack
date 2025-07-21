@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mx-auto">

            <div class="aiz-titlebar text-left mt-2 mb-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h3">{{ trans('messages.all') . ' ' . trans('messages.sliders') }}</h1>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <a href="{{ route('home-slider.create') }}" class="btn btn-primary">
                            <span>{{ trans('messages.add_new') . ' ' . trans('messages.sliders') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <form class="" id="sort_customers" action="" method="GET">

                    <div class="card-body">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Name</th>
                                    <th class="text-center">Link Type</th>
                                    <th>Image</th>
                                    <th>Video</th>
                                    <th class="text-center">Sort Order</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sliders as $key => $slider)
                                    <tr>
                                        <td class="text-center">
                                            {{ $key + 1 + ($sliders->currentPage() - 1) * $sliders->perPage() }}</td>
                                        <td>
                                            {{ $slider->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ ucfirst($slider->link_type) }}
                                        </td>
                                        <td>
                                            <div class="row gutters-5 w-200px w-md-300px mw-100">
                                                @if ($slider->image)
                                                    <div class="col-auto">
                                                        <img src="{{ uploaded_asset($slider->image) }}" alt="Image"
                                                            class="img-fit">
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            @if ($slider->video_file)
                                                <video width="150" height="auto" controls>
                                                    <source src="{{  asset('storage/' . $slider->video_file) }}"
                                                        type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @elseif ($slider->video_url)
                                                <iframe width="150" height="84" src="{{ $slider->video_url }}"
                                                    frameborder="0" allowfullscreen></iframe>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            {{ $slider->sort_order }}
                                        </td>

                                        <td class="text-center">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input onchange="update_published(this)" value="{{ $slider->id }}"
                                                    type="checkbox" {{ $slider->status == 1 ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-soft-primary btn-icon btn-circle"
                                                href="{{ route('home-slider.edit', $slider) }}" title="Edit">
                                                <i class="las la-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle confirm-delete"
                                                data-href="{{ route('home-slider.delete', $slider->id) }}" title="Delete">
                                                <i class="las la-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="aiz-pagination">
                            {{ $sliders->appends(request()->input())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script>
        function update_published(el) {

            var status = 0

            if (el.checked) {
                status = 1;
            }

            $.post('{{ route('home-slider.update-status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Slider updated successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection
