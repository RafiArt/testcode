@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="form-group mb-2">
            <a href="{{ url('categories') }}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
        </div>
        <div class="card">
            <div class="card-header">Detail Kategori</div>

            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Kode</th>
                        <td>{{ $category->kode }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $category->nama }}</td>
                    </tr>
                </table>

                <h5 class="mt-4">Daftar Item dengan Kategori Ini</h5>
                @if($category->masterItems->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Harga Beli</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->masterItems as $item)
                            <tr>
                                <td>{{ $item->kode }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->jenis }}</td>
                                <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ url('master-items/view/' . $item->kode) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted">Tidak ada item dengan kategori ini.</p>
                @endif

                <div class="mt-3">
                    <a class="btn btn-warning" href="{{ url('categories/form/edit/' . $category->id) }}">Edit</a>
                    <a class="btn btn-danger" href="{{ url('categories/delete/' . $category->id) }}" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
