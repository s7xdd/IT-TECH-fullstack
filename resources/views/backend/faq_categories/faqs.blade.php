@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">Freequently Asked Questions</h1>
            </div>
        </div>
    </div>
    <div class="card">
       

        <form class="p-4" action="{{ route('faqs.update') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="category_id" value="{{ $id }}">
            <div class="card-header px-0">
                <h6 class="fw-600 mb-0">{{ $category->name }}</h6>
            </div>
            <div class="card-body px-0">
                
                
                <div class=" repeater">
                    <div data-repeater-list="faqs">
                        
                        @foreach($faqs as $faq)
                            <div data-repeater-item>
                                <hr>
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-from-label">Question:</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="question" class="form-control"  value="{{ $faq->question }}" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-from-label">Answer:</label>
                                    <div class="col-sm-10">
                                        <textarea name="answer" class="form-control">{{ $faq->answer }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-from-label">Order:</label>
                                    <div class="col-sm-10">
                                        <input type="number" name="order" class="form-control"  value="{{ $faq->sort_order }}">
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-danger" data-repeater-delete>Delete</button>
                                </div>
                            </div>
                        @endforeach


                        <div data-repeater-item @if(count($faqs) > 0) style="display: none;" @endif>
                           
                            <div class="form-group row">
                                <label class="col-sm-2 col-from-label">Question:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="question" class="form-control" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-from-label">Answer:</label>
                                <div class="col-sm-10">
                                    <textarea name="answer" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-from-label">Order:</label>
                                <div class="col-sm-10">
                                    <input type="number" name="order" class="form-control" value="0">
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-soft-danger" data-repeater-delete>Delete</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-soft-success" data-repeater-create>Add FAQ</button>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary">Update Page</button>
                <a href="{{ route('faq_categories.index') }}" class="btn btn-cancel mb-0"> Cancel</a>
            </div>
        </form>
    </div>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/jquery.repeater/jquery.repeater.min.js"></script>

<script>
    $(document).ready(function () {
        
        $('.repeater').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
                // updateRepeaterHeadings();
            },
            hide: function(deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                    // updateRepeaterHeadings();
                }
            },
        });
    });
</script>

@endsection
