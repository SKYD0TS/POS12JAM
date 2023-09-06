@extends('layout.layout')

@include('layout.sidebar')
@include('layout.topbar')

@push('styles')
    <style>
        .btn-outline-tertiary {
            color: var(--bs-tertiary);
            border-color: var(--bs-border-color-translucent);
            background-color: transparent;
        }

        .btn-outline-tertiary:hover {
            color: var(--bs-dark);
            background-color: var(--bs-gray-200);
            border-color: var(--bs-border-color-translucent);
        }

        .btn-outline-tertiary:active:hover {
            background-color: var(--bs-gray-300);
            border-color: var(--bs-gray-300);
        }

        .btn-outline-tertiary:focus-visible {
            outline: 0px;
            border: 2px solid black;
        }

        .bi {
            vertical-align: middle;
        }
    </style>
@endpush

@section('main')
    @include('resourceComponent.modalForm')

    <div class="card">
        <button class="btn btn-add btn-primary w-25 mb-4" data-modal_mode="create">Tambah</button>
        {{-- @include('resourceComponent.datatable') --}}

        <hr>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            Launch static backdrop modal
        </button>


        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ...

                        ...

                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Understood</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="NewOrExist">
            <button class="btn btn-secondary w-25 btn-NewOrExist text-start" data-popper-placement="bottom-start"
                data-bs-toggle="dropdown">
                <span class="prefix">prefix : </span>
                <span class="data"></span>
                <span class=""><i class="bi bi-chevron-down fs-6 text-white float-end"></i></span>
            </button>
            <input class="input-noe" hidden name="" id="">
            <div class="dropdown-menu shadow-lg bg-light-subtle px-2 pt-3">
                <div class="input-group shadow-sm">
                    <div class="form-floating">
                        <input name="NewOrExist-Customer" autocomplete="off" data-noe-model='category'
                            class="select-search form-control" data-id id="NewOrExist-Customer" placeholder="Cari">
                        <label for="NewOrExist-Customer">Cari</label>
                    </div>
                    {{-- <button class="btn btn-outline-tertiary"><i>?</i></button> --}}
                </div>
                <div class="list-group search-result-ul w-100 mt-3"
                    style="max-height: 200px; overflow-x: hidden; overflow-y: auto">
                </div>
            </div>
        </div>

        <div class="input-group input-group-sm mb-3">
            <span class="input-group-text" id="inputGroup-sizing-sm">Small</span>
            <input type="text" class="form-control" aria-label="Sizing example input"
                aria-describedby="inputGroup-sizing-sm">
        </div>


    </div>
@endsection
