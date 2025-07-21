@extends('backend.layouts.app', ['body_class' => '', 'title' => 'Partners'])
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="h4">{{ trans('messages.all').' FAQ Categories' }}</h5>
            </div>

            <div class="col-md-6 text-md-right">
                <a href="{{ route('faq_categories.create') }}" class="btn btn-primary">
                    <span>{{ trans('messages.add_new').' FAQ Category' }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @if ($categories)
            <div class="col-lg-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">Sl No.</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" class="text-center">Sort Order</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Manage Questions</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($categories[0]))
                                    @foreach ($categories as $key=>$categ)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>{{ $categ->name }}</td>
                                           
                                            <td class="text-center">{{ $categ->sort_order }}</td>
                                            <td class="text-center">
                                                <b>{!! $categ->is_active == 1 ? '<span class="text-success">Enabled</span>' : '<span class="text-danger">Disabled</span>' !!}</b>
                                            </td>
                                            
                                            <td class="text-center">
                                                <a class="btn btn-success " href="{{ route('faqs.edit', ['id' => base64_encode($categ->id)]) }}"
                                                    title="{{ trans('messages.edit') }}">
                                                    <i class="las la-edit"> FAQ'S</i>
                                                </a>
                                            </td>

                                            <td class="text-center">
                                    
                                                <a class="btn btn-soft-success btn-icon btn-circle" href="{{ route('faq_categories.edit', ['id' => $categ->id]) }}"
                                                    title="{{ trans('messages.edit') }}">
                                                    <i class="las la-edit"></i>
                                                </a>
        
                                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle confirm-delete" data-href="{{ route('faq_categories.delete', $categ->id) }}" title="Delete">
                                                    <i class="las la-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No data found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="aiz-pagination float-right">
                            
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

@push('header')
@endpush
