@extends('backend.layouts.app', ['body_class' => '', 'title' => 'Testimonials'])
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="h4">{{ trans('messages.all').' Testimonials' }}</h5>
            </div>

            <div class="col-md-6 text-md-right">
                <a href="{{ route('testimonials.create') }}" class="btn btn-primary">
                    <span>{{ trans('messages.add_new').' Testimonial' }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @if ($testimonials)
            <div class="col-lg-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Name </th>
                                    <th class="text-left" style="width:20%;">Title</th>
                                    <th class="text-left" style="width:35%;">Comment</th>
                                    <th class="text-center">Sort Order</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($testimonials as $key=>$test)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 + ($testimonials->currentPage() - 1) * $testimonials->perPage() }}</td>
                                        <td>
                                            {{ $test->name }}
                                        </td>
                                        <td class="text-left">{{ $test->title }}</td>
                                        <td class="text-left">{{ $test->comment }}</td>
                                        <td class="text-center">{{ $test->sort_order }}</td>
                                        <td class="text-center">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input onchange="update_status(this)" value="{{ $test->id }}"
                                                    type="checkbox" <?php if ($test->status == 1) {
                                                        echo 'checked';
                                                    } ?>>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('testimonials.edit', $test) }}" class="btn btn-soft-primary btn-icon btn-circle"><i class="las la-edit"></i></a>

                                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle confirm-delete" data-href="{{ route('testimonials.delete', $test->id) }}" title="Delete">
                                                <i class="las la-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="aiz-pagination float-right">
                            {{ $testimonials->appends(request()->input())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script>
        function update_status(el) {

            var status = 0

            if (el.checked) {
                status = 1;
            }

            $.post('{{ route('testimonials.update-status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Status updated successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection
