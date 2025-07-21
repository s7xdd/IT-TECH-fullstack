@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="h4">{{ trans('messages.all').' Blogs' }}</h5>
            </div>

            <div class="col-md-6 text-md-right">
                <a href="{{ route('blog.create') }}" class="btn btn-primary">
                    <span>{{ trans('messages.add_new').' Blog' }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">Blogs</h5>
                    </div>
                    <div class="col-md-4">
                        <form class="" id="sort_blogs" action="" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="search"
                                    name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                                    placeholder="{{ trans('messages.type_name_enter') }}">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('messages.name') }}</th>
                                <th>{{ trans('messages.image') }}</th>
                                <th class="text-center">Blog Date</th>
                                <th class="text-center">{{ trans('messages.status') }}</th>
                                <th class="text-center">{{ trans('messages.options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($blogs as $key => $blog)
                                <tr>
                                    <td>{{ $key + 1 + ($blogs->currentPage() - 1) * $blogs->perPage() }}</td>
                                    <td>{{ $blog->name }}</td>
                                    <td>
                                        <img src="{{ uploaded_asset($blog->image) }}" alt="{{ trans('messages.blog') }}" class="h-50px">
                                    </td>
                                    <td class="text-center">
                                        {{ date('d M, Y', strtotime($blog->blog_date))  }}
                                    </td>
                                    <td class="text-center">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" onchange="update_status(this)" value="{{ $blog->id }}"
                                                <?php if ($blog->status == 1) {
                                                    echo 'checked';
                                                } ?>>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-soft-primary btn-icon btn-circle" href="{{ route('blog.edit', ['id' => $blog->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}" title="{{ trans('messages.edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle confirm-delete" data-href="{{ route('blog.delete', $blog->id) }}" title="Delete">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $blogs->appends(request()->input())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function sort_blogs(el) {
            $('#sort_blogs').submit();
        }

        function update_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('blog.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Blog status updated successfully');
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);

                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);
                }
            });
        }
    </script>
@endsection

