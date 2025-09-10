<div id="filter-container" class="mb-4">
    <h5>Filter</h5>
    <div class="row">
        <div class="col-12 col-md-3">
            <div class="form-group">
                <label>Kode</label>
                <input type="text" class="form-control" id="filter-kode">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" id="filter-nama">
            </div>
        </div>
        <div class="col-12 col-md-2">
            <div class="form-group">
                <label>Harga Min</label>
                <input type="number" class="form-control" id="filter-harga-min">
            </div>
        </div>
        <div class="col-12 col-md-2">
            <div class="form-group">
                <label>Harga Max</label>
                <input type="number" class="form-control" id="filter-harga-max">
            </div>
        </div>
        <div class="col-12 col-md-2 d-flex align-items-end">
            <button class="btn btn-primary mt-1 btn-get-data w-100">Filter</button>
        </div>
    </div>
    <div class="mt-2">
        <span id="loading-filter" style="display: none;" class="text-primary">
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading...
        </span>
    </div>
</div>
