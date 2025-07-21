@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="h4">{{ trans('messages.all').' '.trans('messages.services') }}</h5>
            </div>

            <div class="col-md-6 text-md-right">
                <a href="{{ route('service.create') }}" class="btn btn-primary">
                    <span>{{ trans('messages.add_new').' '.trans('messages.service') }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">{{ trans('messages.services') }}</h5>
                    </div>
                    <div class="col-md-4">
                        <form class="" id="sort_services" action="" method="GET">
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
                                {{-- <th>{{ trans('messages.price') }}</th> --}}
                                <th>{{ trans('messages.image') }}</th>
                                <th class="text-center">Sort Order</th>
                                <th class="text-center">{{ trans('messages.status') }}</th>
                                <th class="text-center">{{ trans('messages.options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $key => $service)
                                <tr>
                                    <td>{{ $key + 1 + ($services->currentPage() - 1) * $services->perPage() }}</td>
                                    <td>{{ $service->getTranslation('name') }}</td>
                                    {{-- <td>{{ $service->price  }}</td> --}}
                                    <td>
                                        <img src="{{ uploaded_asset($service->image) }}" alt="{{ trans('messages.service') }}" class="h-50px">
                                    </td>
                                    <td class="text-center">{{ $service->sort_order  }}</td>
                                    <td class="text-center">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" onchange="update_status(this)" value="{{ $service->id }}"
                                                <?php if ($service->status == 1) {
                                                    echo 'checked';
                                                } ?>>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-soft-primary btn-icon btn-circle"
                                            href="{{ route('service.edit', ['id' => $service->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                            title="{{ trans('messages.edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle confirm-delete" data-href="{{ route('service.delete', $service->id) }}" title="Delete">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $services->appends(request()->input())->links('pagination::bootstrap-5') }}
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
        function sort_services(el) {
            $('#sort_services').submit();
        }

        function update_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('service.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Service status updated successfully');
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

