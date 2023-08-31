@extends('layout.layout')

@include('layout.sidebar')
@include('layout.topbar')

@section('main')
    @include('resourceComponent.modalForm')

    <div class="card">
        <button class="btn btn-add btn-primary w-25 mb-4" data-modal_mode="create">Tambah</button>
        @include('resourceComponent.datatable')
    </div>
@endsection
