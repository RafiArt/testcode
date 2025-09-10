@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Kategori</h5>
            <a href="{{ url('categories/form/new') }}" class="btn btn-primary">Tambah Kategori</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" class="form-control" id="filterKode" placeholder="Filter by Kode" onkeyup="filterCategories()">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" id="filterNama" placeholder="Filter by Nama" onkeyup="filterCategories()">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped" id="tableCategories">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody">
                    <!-- Data akan difilter melalui JavaScript -->
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->kode }}</td>
                        <td>{{ $category->nama }}</td>
                        <td>
                            <a href="{{ url('categories/view') }}/{{ $category->id }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ url('categories/form/edit') }}/{{ $category->id }}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="{{ url('categories/delete') }}/{{ $category->id }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterCategories() {
    // Ambil nilai dari input filter
    const kodeFilter = document.getElementById('filterKode').value.toUpperCase();
    const namaFilter = document.getElementById('filterNama').value.toUpperCase();

    // Ambil semua baris dalam tabel
    const table = document.getElementById('categoriesTableBody');
    const rows = table.getElementsByTagName('tr');

    // Loop melalui semua baris dan sembunyikan yang tidak sesuai dengan filter
    for (let i = 0; i < rows.length; i++) {
        const kodeCell = rows[i].getElementsByTagName('td')[0];
        const namaCell = rows[i].getElementsByTagName('td')[1];

        if (kodeCell && namaCell) {
            const kodeValue = kodeCell.textContent || kodeCell.innerText;
            const namaValue = namaCell.textContent || namaCell.innerText;

            // Periksa apakah baris memenuhi kriteria filter
            const kodeMatch = kodeValue.toUpperCase().indexOf(kodeFilter) > -1;
            const namaMatch = namaValue.toUpperCase().indexOf(namaFilter) > -1;

            // Tampilkan atau sembunyikan baris berdasarkan filter
            if (kodeMatch && namaMatch) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
}
</script>
@endsection
