@php
    $fields = $fields ?? [['name' => 'title', 'type' => 'text'], ['name' => 'content', 'type' => 'text']];

    function custom_field_value($custom, $fname)
    {
        if (is_array($custom)) {
            return $custom[$fname] ?? '';
        } elseif (is_object($custom)) {
            return $custom->$fname ?? '';
        } else {
            return '';
        }
    }
@endphp

<div class="form-group">
    <label>{{ $label ?? 'Custom Fields' }}</label>
    <div class="custom-fields-target{{ isset($field_name) ? '-' . $field_name : '' }}">
        @if (!empty($custom_fields))
            @foreach ($custom_fields as $idx => $custom)
                <div class="row gutters-5 mb-2 align-items-center custom-field-row">

                    @foreach ($fields as $f)
                        @php
                            $field = is_array($f) ? $f : ['name' => $f];
                            $type = $field['type'] ?? 'text';
                            $fname = $field['name'];
                            $placeholder = $field['placeholder'] ?? ucfirst(str_replace('_', ' ', $fname));
                        @endphp
                        <div class="col">
                            @if ($type === 'image')
                                @php
                                    $img = $custom[$fname] ?? '';
                                @endphp
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ trans('messages.browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ trans('messages.choose_file') }}</div>
                                    <input type="hidden"
                                        name="{{ $field_name ?? 'custom_fields' }}[{{ $idx }}][{{ $fname }}]"
                                        value="{{ $img }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            @elseif ($type === 'textarea')
                                <textarea class="form-control mb-2"
                                    name="{{ $field_name ?? 'custom_fields' }}[{{ $idx }}][{{ $fname }}]"
                                    placeholder="{{ $placeholder }}">{{ $custom[$fname] ?? '' }}</textarea>
                            @else
                                <input type="{{ $type }}" class="form-control mb-2"
                                    name="{{ $field_name ?? 'custom_fields' }}[{{ $idx }}][{{ $fname }}]"
                                    placeholder="{{ $placeholder }}" value="{{ $custom[$fname] ?? '' }}">
                            @endif
                        </div>
                    @endforeach

                    <div class="col-auto">
                        <button type="button" class="btn btn-icon btn-circle btn-soft-danger"
                            data-toggle="remove-parent" data-parent=".row">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    @php
        $dataRow = '<div class="row gutters-5 mb-2 align-items-center custom-field-row">';
        foreach ($fields as $f) {
            $field = is_array($f) ? $f : ['name' => $f];
            $type = $field['type'] ?? 'text';
            $fname = $field['name'];
            $placeholder = $field['placeholder'] ?? ucfirst(str_replace('_', ' ', $fname));
            $input = '';
            if ($type === 'textarea') {
                $input .=
                    '<textarea class="form-control mb-2" name="' .
                    ($field_name ?? 'custom_fields') .
                    '[][ ' .
                    $fname .
                    ']" placeholder="' .
                    $placeholder .
                    '"></textarea>';
            } else {
                $input .=
                    '<input type="' .
                    $type .
                    '" class="form-control mb-2" name="' .
                    ($field_name ?? 'custom_fields') .
                    '[][ ' .
                    $fname .
                    ']" placeholder="' .
                    $placeholder .
                    '">';
            }
            $dataRow .= '<div class="col">' . $input . '</div>';
        }
        $dataRow .=
            '<div class="col-auto"><button type="button" class="mt-1 btn btn-icon btn-circle btn-soft-danger" data-toggle="remove-parent" data-parent=".row"><i class="las la-times"></i></button></div></div>';
    @endphp

    <button type="button" class="btn btn-soft-secondary border bg-gray-300 mt-2" data-toggle="add-more"
        data-content='{{ $dataRow }}'
        data-target=".custom-fields-target{{ isset($field_name) ? '-' . $field_name : '' }}">Add Custom Field</button>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function imagePreviewHandler(input, previewId) {
                const preview = document.querySelector(previewId);
                const file = input.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                } else if (preview) {
                    preview.src = '';
                    preview.classList.add('d-none');
                }
            }

            document.querySelectorAll('.custom-image-input').forEach(function(input) {
                input.addEventListener('change', function() {
                    const previewId = this.getAttribute('data-preview');
                    imagePreviewHandler(this, previewId);
                });
            });
            document.addEventListener('change', function(e) {
                if (e.target.matches('.custom-image-input')) {
                    const previewId = e.target.getAttribute('data-preview');
                    imagePreviewHandler(e.target, previewId);
                }
            }, true);
        });
    </script>
@endpush
