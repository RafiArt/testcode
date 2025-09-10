@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="form-group mb-2">
            <a href="{{ url('master-items') }}" class="btn btn-secondary">Kembali ke Daftar Item</a>
        </div>
        <div class="card">
            <div class="card-header">Detail Master Item</div>

            <div class="card-body">
                @if($data->foto)
                <div class="text-center mb-4">
                    <!-- PERBAIKAN: Gunakan url() bukan asset() -->
                    <img src="{{ Storage::url('foto_items/' . $data->foto) }}"
                         alt="Foto Item"
                         class="img-fluid rounded"
                         style="max-height: 300px; object-fit: cover;">
                </div>
                @else
                <div class="text-center mb-4">
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                        <span class="text-muted">Tidak ada foto</span>
                    </div>
                </div>
                @endif

                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Kode</th>
                        <td>{{ $data->kode }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $data->nama }}</td>
                    </tr>
                    <tr>
                        <th>Harga Beli</th>
                        <td>Rp {{ number_format($data->harga_beli, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Laba</th>
                        <td>{{ $data->laba }}%</td>
                    </tr>
                    <tr>
                        <th>Harga Jual</th>
                        <td>Rp {{ number_format($data->harga_beli + ($data->harga_beli * $data->laba / 100), 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Supplier</th>
                        <td>{{ $data->supplier }}</td>
                    </tr>
                    <tr>
                        <th>Jenis</th>
                        <td>{{ $data->jenis }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>
                            @if($data->categories->count() > 0)
                                @foreach($data->categories as $category)
                                    <span class="badge bg-primary">{{ $category->nama }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">Tidak ada kategori</span>
                            @endif
                        </td>
                    </tr>
                </table>

                <div class="mt-3 d-flex gap-2">
                    <a class="btn btn-info" href="{{ url('master-items/form/edit/' . $data->id) }}">Edit</a>
                    <form action="{{ url('master-items/delete/' . $data->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?');">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
