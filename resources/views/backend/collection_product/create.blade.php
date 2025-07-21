@extends('backend.layouts.app')

@section('content')
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ isset($collection) ? 'Edit' : 'Add' }} Collection Product</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal"
                    action="{{ isset($collection) ? route('collection_product.update', $collection->id) : route('collection_product.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($collection))
                        @method('PUT')
                    @endif

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">Page</label>
                        <div class="col-lg-9">
                            <select name="page" class="form-control aiz-selectpicker">
                                <option value="">Select Page</option>
                                @foreach ($pageOptions as $key => $label)
                                    <option value="{{ $key }}" @if (isset($collection) && $collection->page === $key) selected @endif>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">Page Reference</label>
                        <div class="col-lg-9">
                            <select name="page_reference" class="form-control aiz-selectpicker">
                                <option value="">Select Reference</option>
                                @foreach ($pageReferenceOptions as $key => $label)
                                    <option value="{{ $key }}" @if (isset($collection) && $collection->page_reference === $key) selected @endif>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label" for="collectiontitle">Title</label>
                        <div class="col-lg-9">
                            <input type="text" name="collectiontitle" class="form-control"
                                value="{{ $collection->collectiontitle ?? old('collectiontitle') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label" for="collectiondescription">Description</label>
                        <div class="col-lg-9">
                            <textarea name="collectiondescription" class="form-control">{{ $collection->collectiondescription ?? old('collectiondescription') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">Image 1</label>
                        <div class="col-lg-9">
                            <input type="file" name="collectionimage1" class="form-control">
                            @if (isset($collection) && $collection->collectionimage1)
                                <img src="{{ asset('storage/' . $collection->collectionimage1) }}" width="80"
                                    class="mt-2">
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">Image 2</label>
                        <div class="col-lg-9">
                            <input type="file" name="collectionimage2" class="form-control">
                            @if (isset($collection) && $collection->collectionimage2)
                                <img src="{{ asset('storage/' . $collection->collectionimage2) }}" width="80"
                                    class="mt-2">
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">Products</label>
                        <div class="col-lg-9">
                            <select name="products[]" class="form-control aiz-selectpicker" multiple required>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @if (isset($collection) && $collection->products->contains($product->id)) selected @endif>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('collection_product.index') }}" class="btn btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
