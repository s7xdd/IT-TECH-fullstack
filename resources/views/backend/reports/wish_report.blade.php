@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">Product Wish Report</h1>
	</div>
</div>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('wish_report.index') }}" method="GET">
                    <div class="form-group row">
                        
                        {{-- <div class="col-md-6">
                            <label class="col-md-2 col-form-label">Sort by Category:</label>
                            <select id="demo-ease" class="from-control aiz-selectpicker"  data-width="100%" name="category_id" required>
                                @foreach (\App\Models\Category::all() as $key => $category)
                                    <option value="{{ $category->id }}" @if($category->id == $sort_by) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="col-sm-6 col-md-4">
                            <label class="col-form-label">Sort by Category :</label>
                            <select id="demo-ease" class="from-control aiz-selectpicker" data-width="100%" data-live-search="tru" name="category_id" data-selected={{ $sort_by }}>
                                @foreach (getAllCategories()->where('parent_id', 0) as $item)
                                    <option value="{{ $item->id }}" @if( $sort_by == $item->id)  {{ 'selected' }} @endif )>{{ $item->name }}</option>
                                    @if ($item->child)
                                        @foreach ($item->child as $cat)
                                            @include('backend.product.categories.menu_child_category', [
                                                'category' => $cat,
                                                'old_data' => $sort_by,
                                            ])
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-sm-6 col-md-4">
                            <label class="col-form-label">Search Product Name/SKU :</label>
                            <input type="text" class="form-control form-control-sm" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Type & Enter">
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="col-form-label"></label>
                            <button class="btn btn-warning mt-4" type="submit">Filter</button>
                            <a class="btn btn-info mt-4" href="{{ route('wish_report.index') }}" >Reset</a>
                            <a href="{{ route('export.wish_report') }}"  class="btn btn-danger mt-4" style="border-radius: 30px;">Excel Export</a>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">Sl No.</th>
                            <th>Product SKU</th>
                            <th>Product Name</th>
                            <th class="text-center">Number of Wish</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            {{-- @if($product->wishlists != null)
                               
                            @endif --}}
                            <tr>
                                <td class="text-center">{{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                <td> {{ $product->sku }} </td>
                                <td>{{ $product->name }}</td>
                                <td class="text-center">{{ $product->wishlists->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                    {{ $products->appends(request()->input())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
