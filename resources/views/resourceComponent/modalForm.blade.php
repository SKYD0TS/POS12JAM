<div class="modal fade" id="model-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="model-form" method="POST" action="/" onsubmit="return false;">
                    @csrf
                    <div id="form-method"></div>
                    @foreach ($formColumns as $col)
                        @if ($col['input_type'] == 'reg')
                            <div class="form-floating mb-4">
                                <input type={{ $col['type'] }} name={{ $col['name'] }} class="form-control "
                                    id={{ $col['name'] }} placeholder=" " value={{ old($col['name']) }}>
                                <label for={{ $col['name'] }}>{{ $col['label'] }}</label>
                                <div class="invalid-feedback"></div>
                            </div>
                        @elseif($col['input_type'] == 'textarea')
                            <div class="form-floating mb-4">
                                <textarea class="form-control" name={{ $col['name'] }} placeholder=" " id={{ $col['name'] }} style="height: 100px;">{{ old($col['name']) }}</textarea>
                                <label for={{ $col['name'] }}>{{ $col['label'] }}</label>
                                <div class="invalid-feedback"></div>
                            </div>
                        @elseif($col['input_type'] == 'NewOrExist')
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" name={{ $col['name'] }} type="checkbox"
                                    id={{ $col['name'] }}
                                    @isset($col['inputs'])
                                        data-inputs='{{ implode(',', $col['inputs']) }}'
                                    @endisset>
                                <label class="form-check-label" for={{ $col['name'] }}>
                                    {{ $col['title'] }}
                                </label>
                                <div class="invalid-feedback"></div>
                            </div>
                            <label for={{ $col['label'] }} class="form-label">{{ $col['label'] }}</label>
                            <select class="form-select select-searchable" data-dropdown-parent="#modalName"
                                data-plugin-selectTwo id="search-exist-{{ $col['name'] }}">
                            </select>
                        @elseif($col['input_type'] == 'select_dropdown')
                            <div class="mb-4">
                                <label for={{ $col['label'] }} class="form-label">{{ $col['label'] }}</label>
                                <select class="form-select " name={{ $col['name'] }}>
                                    <option value=''selected>-</option>
                                    @foreach ($col['selections'] as $option)
                                        @if (old($col['name']) == $option->id)
                                            <option value={{ $option->id }} selected>{{ $option->name }}</option>
                                        @else
                                            <option value={{ $option->id }}>{{ $option->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        @elseif($col['input_type'] == 'select_search')
                            <div class="mb-4">
                                <label for={{ $col['label'] }} class="form-label">{{ $col['label'] }}</label>
                                <select class="form-select select-searchable" name={{ $col['name'] }}>
                                    <option value=''selected>-</option>
                                    @foreach ($col['selections'] as $option)
                                        @if (old($col['name']) == $option->id)
                                            <option value={{ $option->id }} selected>{{ $option->name }}</option>
                                        @else
                                            <option value={{ $option->id }}>{{ $option->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        @endif
                    @endforeach
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-form_submit btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        const NoE = $('#NewOrExist'),
            inputs = NoE.data('inputs').split(',')

        NoE.on('change', function(e) {
            console.log($(e.target).is(':checked'))
            $('.select-searchable').select2({
                dropdownParent: $('#model-modal')
            });
            if ($(e.target).is(':checked')) {
                inputs.forEach(function(i) {
                    $(`#${i}`).hide("fast")
                })
            } else {
                inputs.forEach(function(i) {
                    $(`#${i}`).show("fast")
                })
            }
        })
    </script>
@endpush
