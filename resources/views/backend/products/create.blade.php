@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ trans('messages.add').' '.trans('messages.new').' '.trans('messages.product') }}</h5>
    </div>
    <div class="">
        <form class="form form-horizontal mar-top" id="addNewProduct" action="{{ route('products.store') }}" method="POST"
            enctype="multipart/form-data" id="choice_form">
            <div class="row gutters-5">
                <div class="col-lg-8">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                
                                <label class="col-md-3 col-from-label">{{ trans('messages.product').' '.trans('messages.name') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="name" placeholder="{{ trans('messages.product').' '.trans('messages.name') }}"
                                    onkeyup="title_update(this)" required>
                                </div>
                            </div>
                            <div class="form-group row" id="category">
                                <label class="col-md-3 col-from-label">{{ trans('messages.category') }} <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="category_id" id="category_id"
                                        data-live-search="true" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}
                                            </option>
                                            @foreach ($category->childrenCategories as $childCategory)
                                                @include('backend.categories.child_category', [
                                                    'child_category' => $childCategory,
                                                ])
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" id="brand">
                                <label class="col-md-3 col-from-label">{{ trans('messages.brand') }}</label>
                                <div class="col-md-8">
                                    @php   
                                        $brands = \App\Models\Brand::where('is_active',1)->orderBy('name','asc')->get();
                                    @endphp
                                    <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                        data-live-search="true">
                                        <option value="">{{ trans('messages.select').' '.trans('messages.brand') }}</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        
                           
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.tags') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control aiz-tag-input" name="tags[]"
                                        placeholder="{{ trans('messages.type_hit_enter_add_tag') }}">
                                    <small class="text-muted">{{ trans('messages.tag_details') }}</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{ trans('messages.slug') }}<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="{{ trans('messages.slug') }}" id="slug" name="slug" required
                                        class="form-control">
                                    @error('slug')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.images') }}</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">{{ trans('messages.gallery_images') }}<small>({{ trans('messages.1000*1000') }})</small></label>
                                <div class="col-md-8">
                                    <input type="file" name="gallery_images[]" multiple class="form-control"
                                        accept="image/*" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">{{ trans('messages.thumbnail_image') }}
                                    <small>({{ trans('messages.1000*1000') }})</small></label>
                                <div class="col-md-8">
                                    <input type="file" name="thumbnail_image" class="form-control" accept="image/*" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.discounts') }}</h5>
                        </div>
                        <div class="card-body">
                           
                            <div class="form-group row">
                                <label class="col-sm-3 control-label" for="date_range">{{ trans('messages.discount').' '.trans('messages.date').' '.trans('messages.range') }}  </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control aiz-date-range" id="date_range"
                                        name="date_range" placeholder="{{ trans('messages.select').' '.trans('messages.date') }}" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.discount') }}</label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ trans('messages.discount') }}" name="discount" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control aiz-selectpicker" name="discount_type">
                                        <option value="amount">{{ trans('messages.flat') }}</option>
                                        <option value="percent">{{ trans('messages.percent') }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                                            
                    <div class="card product-repeater">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.details') }}</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.sku') }} </label>
                                <div class="col-md-6">
                                    <input type="text" placeholder="{{ trans('messages.sku') }}" name="sku" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.quantity') }} <span  class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ trans('messages.quantity') }}" name="current_stock" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.price') }} <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ trans('messages.price') }}" name="price" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.vat') }} (%) </label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="VAT" name="vat" class="form-control">
                                </div>
                            </div> 

                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.description') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-12 col-from-label">{{trans('messages.description') }}</label>
                                <div class="col-md-12">
                                    <textarea class="aiz-text-editor" data-min-height="300" name="description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card repeater">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.tabs') }}</h5>
                        </div>
                        <div class="card-body">
                            <div data-repeater-list="tabs">
                                <div data-repeater-item>
                                    <div class="form-group row">
                                        <label class="col-md-12 col-from-label">{{ trans('messages.heading') }}</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="tab_heading">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12 col-from-label">{{ trans('messages.description') }}</label>
                                        <div class="col-md-12">
                                            <textarea class="text-area" name="tab_description"></textarea>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12 text-right">
                                            <input data-repeater-delete type="button" class="btn btn-danger action-btn" value="{{ trans('messages.delete') }}" />
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <input data-repeater-create type="button" class="btn btn-success action-btn"
                                value="{{ trans('messages.add') }}" />
                        </div>
                    </div>
                    

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.video') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.video').' '.trans('messages.provider') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="video_provider"
                                        id="video_provider">
                                        <option value="youtube">{{ trans('messages.youtube') }}</option>
                                        <option value="vimeo">{{ trans('messages.vimeo') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.video').' '.trans('messages.link') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="video_link"
                                        placeholder="{{ trans('messages.video').' '.trans('messages.link') }}">
                                    <small class="text-muted">
                                        {{-- Use proper link without extra parameter. Don't use short share link/embeded iframe code. --}}
                                        </small>
                                </div>
                            </div>
                        </div>
                    </div>

                  
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.seo_section') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.meta_title') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="meta_title"
                                        placeholder="{{ trans('messages.meta_title') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.meta_description') }}</label>
                                <div class="col-lg-8">
                                    <textarea name="meta_description" rows="8" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.meta_keywords') }}</label>
                                <div class="col-md-8">
                                    {{-- data-max-tags="1" --}}
                                    <input type="text" class="form-control aiz-tag-input" name="meta_keywords[]"
                                        placeholder="{{ trans('messages.type_hit_enter_add_keyword') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.og_title') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="og_title" placeholder="{{ trans('messages.og_title') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.og_description') }}</label>
                                <div class="col-lg-8">
                                    <textarea name="og_description" rows="8" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.twitter_title') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="twitter_title"
                                        placeholder="{{ trans('messages.twitter_title') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.twitter_description') }}</label>
                                <div class="col-lg-8">
                                    <textarea name="twitter_description" rows="8" class="form-control"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4">

                    <div class="card bg-transparent shadow-none border-0">
                        <div class="card-body p-0">
                            <div class="btn-toolbar justify-content-end" role="toolbar"
                                aria-label="Toolbar with button groups">
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                    <button type="submit" name="button" value="draft"
                                        class="btn btn-cancel action-btn">{{ trans('messages.save_draft') }}</button>
                                </div>
                               
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <button type="submit" name="button" value="publish"
                                        class="btn btn-success action-btn">{{ trans('messages.save_publish') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.return_refund').' '.trans('messages.status') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ trans('messages.status') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="return_refund" value="0">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group mr-2" role="group" aria-label="First group">
                            <button type="submit" name="button" value="draft" class="btn btn-cancel action-btn">{{ trans('messages.save_draft') }}</button>
                        </div>
                       
                        <div class="btn-group" role="group" aria-label="Second group">
                            <button type="submit" name="button" value="publish"
                                class="btn btn-success action-btn">{{ trans('messages.save_publish') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('styles')
<style>
   
</style>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"
        integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
      
        let buttons = [
                    ["font", ["bold", "underline", "italic", "clear"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["style", ["style"]],
                    ["color", ["color"]],
                    ["table", ["table"]],
                    ["insert", ["link", "picture", "video"]],
                    ["view", ["fullscreen", "undo", "redo"]],
                ];
        $('.description-text-area').summernote({
            toolbar: buttons,
            height: 300,
            callbacks: {
                onImageUpload: function(data) {
                    data.pop();
                },
                onPaste: function(e) {
                    if (format) {
                        var bufferText = ((e.originalEvent || e).clipboardData || window
                            .clipboardData).getData('Text');
                        e.preventDefault();
                        document.execCommand('insertText', false, bufferText);
                    }
                }
            }
        });



        $('.repeater').repeater({
            initEmpty: true,
            show: function() {
                $(this).slideDown();

                note = $(this).find('.text-area').summernote({
                    toolbar: buttons,
                    height: 300,
                    callbacks: {
                        onImageUpload: function(data) {
                            data.pop();
                        },
                        onPaste: function(e) {
                            if (format) {
                                var bufferText = ((e.originalEvent || e).clipboardData || window
                                    .clipboardData).getData('Text');
                                e.preventDefault();
                                document.execCommand('insertText', false, bufferText);
                            }
                        }
                    }
                });

                var nativeHtmlBuilderFunc = note.summernote('module', 'videoDialog').createVideoNode;

                note.summernote('module', 'videoDialog').createVideoNode = function(url) {
                    var wrap = $('<div class="embed-responsive embed-responsive-16by9"></div>');
                    var html = nativeHtmlBuilderFunc(url);
                    html = $(html).addClass('embed-responsive-item');
                    return wrap.append(html)[0];
                };

            },
            hide: function(deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            },
        });

    </script>

    <script type="text/javascript">
        $('form').bind('submit', function(e) {
            if ($(".action-btn").attr('attempted') == 'true') {
                e.preventDefault();
            } else {
                $(".action-btn").attr("attempted", 'true');
            }
        });

        function title_update(e) {
            title = e.value;
            title = title.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')
            $('#slug').val(title)
        }

      
    </script>
@endsection
