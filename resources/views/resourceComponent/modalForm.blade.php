<div class="modal fade" id="model-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="model-form" method="POST" action="/" onsubmit="return false;" autofocus>

            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div id="form-method"></div>
                    @foreach ($formColumns as $col)
                        <div class="mb-4">
                            @if ($col['input_type'] == 'reg')
                                <div class="form-floating">
                                    <input type={{ $col['type'] }} name={{ $col['name'] }} class="form-control "
                                        id={{ $col['name'] }} placeholder=" " value={{ old($col['name']) }}>
                                    <label for={{ $col['name'] }}>{{ $col['label'] }}@if (str_contains($formRules[$col['name']], 'required'))
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            @elseif($col['input_type'] == 'select_dropdown')
                                <div class="input-group">
                                    <label for={{ $col['label'] }} class="input-group-text">
                                        {{ $col['label'] }} :
                                        @if (str_contains($formRules[$col['name']], 'required'))
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    <select class="form-select form-control" id={{ $col['name'] }}
                                        name={{ $col['name'] }}>
                                        <option value=''selected selected>-</option>
                                        @foreach ($col['selections'] as $option)
                                            <option value={{ data_get($option, $col['selection_data'][0]) }}>
                                                {{ data_get($option, $col['selection_data'][1]) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            @elseif($col['input_type'] == 'textarea')
                                <div class="form-floating">
                                    <textarea class="form-control" name={{ $col['name'] }} placeholder=" " id={{ $col['name'] }}
                                        style="height: 110px; resize: none">{{ old($col['name']) }}</textarea>
                                    <label for={{ $col['name'] }}>
                                        {{ $col['label'] }}
                                        @if (str_contains($formRules[$col['name']], 'required'))
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            @elseif($col['input_type'] == 'NewOrExist')
                                <div class="NewOrExist">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" name="noe-{{ $col['name'] }}" type="checkbox"
                                            value="" id="flexCheckDefault">
                                        <div class="invalid-feedback"></div>
                                        <label class="form-check-label"
                                            for="flexCheckDefault">{{ $col['label'] }}</label>
                                    </div>
                                    <div class="noe-container" style="display: none">
                                        <label class="form-check-label"></label>
                                        <button class="btn btn-secondary btn-NewOrExist text-start"
                                            data-popper-placement="bottom-start" data-bs-toggle="dropdown"
                                            data-inputs='{{ implode(',', $col['inputs']) }}'>
                                            {{-- <span class="prefix">{{$col['name']}} : </span> --}}
                                            <span class="data" data-default="Cari : --- ">Cari : --- </span>
                                            <span class="">&nbsp;<i
                                                    class="bi bi-chevron-down fs-6 text-white float-end"></i></span>
                                        </button>
                                        <input class="input-noe" hidden name={{ $col['name'] }}
                                            id={{ $col['name'] }}>
                                        <div class="dropdown-menu shadow-lg bg-light-subtle px-2 pt-3">
                                            <div class="input-group shadow-sm">
                                                <div class="form-floating">
                                                    <input name="NewOrExist-{{ $col['name'] }} " autocomplete="off"
                                                        data-noe-model={{ $col['name'] }}
                                                        class="select-search form-control" data-id
                                                        id="NewOrExist-{{ $col['name'] }} " placeholder="Cari">
                                                    <label for="NewOrExist-{{ $col['name'] }} ">Cari</label>
                                                </div>
                                                {{-- <button class="btn btn-outline-tertiary"><i>?</i></button> --}}
                                            </div>
                                            <div class="list-group search-result-ul w-100 mt-3"
                                                style="max-height: 200px; overflow-x: hidden; overflow-y: auto">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <div class="text-danger">*harus diisi</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-form_submit btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@include('scripts.NoE')
@push('scripts')
    <script>
        const noe = $('.NewOrExist')
        $('.NewOrExist').on('change', 'input[type=checkbox]', function(e) {
            const inputs = noe.find('button.btn-NewOrExist').data('inputs').split(',')
            console.log($(e.target).is(':checked'))
            if ($(e.target).is(':checked')) {
                inputs.forEach(function(i) {
                    $(`#${i}`).hide("fast")
                    noe.find('.noe-container').show('fast')
                })
            } else {
                inputs.forEach(function(i) {
                    $(`#${i}`).show("fast")
                    noe.find('.noe-container').hide('fast')
                })
            }
        })
    </script>
@endpush
