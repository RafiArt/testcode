@extends('layouts.app')

@section('title', 'Master Items')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Daftar Master Items</h4>
                <a href="{{ url('master-items/form/new') }}" class="btn btn-primary">Tambah Item</a>
            </div>
            <div class="card-body">
                @include('master_items.index.filter')
                @include('master_items.index.table')
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@include('master_items.index.js')
@endsection
