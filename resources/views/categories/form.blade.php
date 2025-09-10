@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                {{ $method == 'new' ? 'Tambah' : 'Edit' }} Kategori
            </div>
            <div class="card-body">
                <form method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label>Kode</label>
                        <input type="text" class="form-control" name="kode" required value="{{ $item->kode ?? '' }}">
                    </div>

                    <div class="form-group mb-3">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" required value="{{ $item->nama ?? '' }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ url('categories') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
