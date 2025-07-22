@php
    $fields = $fields ?? [['name' => 'title', 'type' => 'text'], ['name' => 'content', 'type' => 'text']];
    $custom_fields = $custom_fields ?? [];

@endphp

<div class="form-group">
    <label>{{ $label ?? 'Custom Fields' }}</label>
    <div class="custom-fields-target{{ isset($field_name) ? '-' . $field_name : '' }}">
        @if (!empty($custom_fields))
            @foreach ($custom_fields as $idx => $custom)
                @continue(!is_numeric($idx))
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
                                @php $img = $custom[$fname] ?? ''; @endphp
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ trans('messages.browse') }}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ trans('messages.choose_file') }}</div>
                                    <input type="hidden"
                                        name="{{ $field_name ?? 'custom_fields' }}[{{ $idx }}][{{ $fname }}]"
                                        value="{{ $img }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
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
        $newIndex = '__INDEX__';
        $dataRow = '<div class="row gutters-5 mb-2 align-items-center custom-field-row">';
        foreach ($fields as $f) {
            $field = is_array($f) ? $f : ['name' => $f];
            $type = $field['type'] ?? 'text';
            $fname = $field['name'];
            $placeholder = $field['placeholder'] ?? ucfirst(str_replace('_', ' ', $fname));
            $input = '';

            if ($type === 'image') {
                $input .= '<div class="input-group" data-toggle="aizuploader" data-type="image">';
                $input .=
                    '<div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">' .
                    trans('messages.browse') .
                    '</div></div>';
                $input .= '<div class="form-control file-amount">' . trans('messages.choose_file') . '</div>';
                $input .=
                    '<input type="hidden" name="' .
                    ($field_name ?? 'custom_fields') .
                    '[' .
                    $newIndex .
                    '][' .
                    $fname .
                    ']" class="selected-files">';
                $input .= '</div><div class="file-preview box sm"></div>';
            } elseif ($type === 'textarea') {
                $input .=
                    '<textarea class="form-control mb-2" name="' .
                    ($field_name ?? 'custom_fields') .
                    '[' .
                    $newIndex .
                    '][' .
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
                    '[' .
                    $newIndex .
                    '][' .
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
        data-content="{!! str_replace('"', '&quot;', $dataRow) !!}"
        data-target=".custom-fields-target{{ isset($field_name) ? '-' . $field_name : '' }}">
        Add Custom Field
    </button>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            console.log('hhhhhhh');

            function getNextIndex(target) {
                let maxIndex = -1;
                target.querySelectorAll('.custom-field-row').forEach(el => {
                    el.querySelectorAll('input[name], textarea[name]').forEach(inp => {
                        const match = inp.name.match(/\[(\d+)\]/);
                        if (match) {
                            const idx = parseInt(match[1]);
                            if (!isNaN(idx)) {
                                maxIndex = Math.max(maxIndex, idx);
                            }
                        }
                    });
                });
                return maxIndex + 1;
            }



            function decodeHtml(html) {
                const txt = document.createElement('textarea');
                txt.innerHTML = html;
                return txt.value;
            }

            document.addEventListener('click', function(e) {
                const addMoreBtn = e.target.closest('[data-toggle=add-more]');
                if (!addMoreBtn) return;

                e.preventDefault();

                const target = document.querySelector(addMoreBtn.dataset.target);
                const nextIndex = getNextIndex(target);

                // Decode the escaped HTML string back to actual HTML
                let rawTemplate = decodeHtml(addMoreBtn.dataset.content);

                // Replace placeholder __INDEX__ with actual index
                rawTemplate = rawTemplate.replace(/__INDEX__/g, nextIndex);

                const templateEl = document.createElement('template');
                templateEl.innerHTML = rawTemplate.trim();

                const newRow = templateEl.content.firstElementChild;
                if (newRow) target.appendChild(newRow);
            });


            if (e.target.closest('[data-toggle=remove-parent]')) {
                const parent = e.target.closest(e.target.getAttribute('data-parent'));
                if (parent) parent.remove();
            }


        });
    </script>
@endpush
