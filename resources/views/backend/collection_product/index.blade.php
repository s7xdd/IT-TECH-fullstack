@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">All Collection Products</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('collection_product.create') }}" class="btn btn-circle btn-info">
                    <span>Add New Collection</span>
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6 mr-4">Collections</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table p-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Image1</th>
                        <th>Image2</th>
                        <th>Page</th>
                        <th>Page Reference</th>
                        <th>Products</th>
                        <th>Description</th>
                        <th width="10%">Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($collections as $key => $collection)
                        <tr>
                            <td>{{ $key + 1 + ($collections->currentPage() - 1) * $collections->perPage() }}</td>
                            <td>{{ $collection->collectiontitle }}</td>
                            <td>
                                @if ($collection->collectionimage1)
                                    <img src="{{ asset('storage/' . $collection->collectionimage1) }}" width="60">
                                @endif
                            </td>
                            <td>
                                @if ($collection->collectionimage2)
                                    <img src="{{ asset('storage/' . $collection->collectionimage2) }}" width="60">
                                @endif
                            </td>

                            <td>{{ $collection->page }}</td>
                            <td>{{ $collection->page_reference }}</td>

                            <td>
                                @foreach ($collection->products as $product)
                                    <span class="badge badge-info">{{ $product->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ Str::limit($collection->collectiondescription, 40) }}</td>
                            <td>
                                <a class="btn btn-soft-primary btn-icon btn-circle"
                                    href="{{ route('collection_product.edit', $collection->id) }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <form action="{{ route('collection_product.destroy', $collection->id) }}" method="POST"
                                    style="display:inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-soft-danger btn-icon btn-circle confirm-delete">
                                        <i class="las la-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $collections->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
