@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                {{ $method == 'new' ? 'Tambah' : 'Edit' }} Master Item
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($method == 'edit')
                    <div class="form-group mb-3">
                        <label>Kode Barang</label>
                        <input type="text" class="form-control" name="kode_barang" required readonly value="{{ $item->kode ?? '' }}">
                    </div>
                    @endif

                    <div class="form-group mb-3">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" required value="{{ $item->nama ?? '' }}">
                    </div>

                    <div class="form-group mb-3">
                        <label>Harga Beli</label>
                        <input type="number" class="form-control" name="harga_beli" required value="{{ $item->harga_beli ?? '' }}">
                    </div>

                    <div class="form-group mb-3">
                        <label>Laba (dalam persen)</label>
                        <input type="number" class="form-control" name="laba" required value="{{ $item->laba ?? '' }}">
                    </div>

                    @php $selected = $item->supplier ?? ''; @endphp
                    <div class="form-group mb-3">
                        <label>Supplier</label>
                        <select class="form-control" required name="supplier">
                            <option value="">--Pilih--</option>
                            <option value="Tokopaedi" @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
                            <option value="Bukulapuk" @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
                            <option value="TokoBagas" @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
                            <option value="E Commurz" @if($selected == 'E Commurz') selected @endif>E Commurz</option>
                            <option value="Blublu" @if($selected == 'Blublu') selected @endif>Blublu</option>
                        </select>
                    </div>

                    @php $selected = $item->jenis ?? ''; @endphp
                    <div class="form-group mb-3">
                        <label>Jenis</label>
                        <select class="form-control" required name="jenis">
                            <option value="">--Pilih--</option>
                            <option value="Obat" @if($selected == 'Obat') selected @endif>Obat</option>
                            <option value="Alkes" @if($selected == 'Alkes') selected @endif>Alkes</option>
                            <option value="Matkes" @if($selected == 'Matkes') selected @endif>Matkes</option>
                            <option value="Umum" @if($selected == 'Umum') selected @endif>Umum</option>
                            <option value="ATK" @if($selected == 'ATK') selected @endif>ATK</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Kategori</label>
                        <select class="form-control select2" name="categories[]" multiple>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    @if(isset($item) && $item->categories->contains($category->id)) selected @endif>
                                    {{ $category->nama }} ({{ $category->kode }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Foto</label>
                        <input type="file" class="form-control" name="foto" accept="image/*">
                        @if(isset($item) && $item->foto)
                            <div class="mt-2">
                                <img src="{{ asset('storage/foto_items/' . $item->foto) }}" alt="Foto Item" style="max-width: 200px;">
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    <a href="{{ url('master-items') }}" class="btn btn-secondary mt-3">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Pilih kategori",
            allowClear: true
        });
    });
</script>
@endsection
