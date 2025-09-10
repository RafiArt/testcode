<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        var dataTable = $('#table').DataTable({
            searching: false,
            order: [[0, 'desc']],
            columns: [
                { data: 'kode' },
                { data: 'nama' },
                { data: 'jenis' },
                {
                    data: 'harga_beli',
                    render: function(data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'harga_jual',
                    render: function(data, type, row) {
                        return formatRupiah(data);
                    }
                },
                { data: 'supplier' },
                {
                    data: 'foto',
                    render: function(data, type, row) {
                        if (data) {
                            // PERBAIKAN PATH FOTO
                            return '<img src="{{ asset("storage/foto_items") }}/' + data + '" width="50" height="50" class="img-thumbnail" style="object-fit: cover;">';
                        }
                        return '-';
                    }
                },
                {
                    data: 'action',
                    render: function(data, type, row) {
                        return '<a href="{{url("master-items/view")}}/' + row.kode + '" class="btn btn-primary btn-sm">View</a>';
                    }
                }
            ]
        });

        // Fungsi format Rupiah
        function formatRupiah(angka) {
            var number_string = angka.toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        // Event handler untuk tombol filter
        $('.btn-get-data').click(function() {
            getData();
        });

        // Fungsi untuk mengambil data
        function getData() {
            $('#loading-filter').show();
            dataTable.clear().draw();

            var filter_kode = $('#filter-kode').val();
            var filter_nama = $('#filter-nama').val();
            var filter_harga_min = $('#filter-harga-min').val();
            var filter_harga_max = $('#filter-harga-max').val();

            $.ajax({
                url: '{{ url("master-items/search") }}',
                method: 'GET',
                dataType: 'json',
                data: {
                    kode: filter_kode,
                    nama: filter_nama,
                    hargamin: filter_harga_min,
                    hargamax: filter_harga_max
                },
                tryCount: 0,
                retryLimit: 3,
                success: function(response) {
                    if (response.status === 200) {
                        // Format data untuk DataTable
                        var formattedData = response.data.map(function(item) {
                            var harga_jual = item.harga_beli + (item.harga_beli * item.laba / 100);
                            harga_jual = Math.round(harga_jual);

                            return {
                                kode: item.kode,
                                nama: item.nama,
                                jenis: item.jenis,
                                harga_beli: item.harga_beli,
                                harga_jual: harga_jual,
                                supplier: item.supplier,
                                foto: item.foto,
                                action: item.kode
                            };
                        });

                        dataTable.rows.add(formattedData).draw();
                    }
                    $('#loading-filter').hide();
                },
                error: function(xhr, textStatus, errorThrown) {
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        $.ajax(this);
                        return;
                    }
                    alert('Terjadi kesalahan server, tidak dapat mengambil data');
                    $('#loading-filter').hide();
                }
            });
        }

        // Load data pertama kali
        getData();
    });
</script>
