<script>
    $(document).ready(function() {
        console.log('Image URL:', window.APP_CONFIG.imageUrl);

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
                        if (data && data.trim() !== '') {
                            var imageUrl = window.APP_CONFIG.imageUrl + '/' + data;
                            console.log('Loading image:', imageUrl);

                            return '<img src="' + imageUrl + '" ' +
                                   'width="50" height="50" ' +
                                   'class="img-thumbnail" ' +
                                   'style="object-fit: cover;" ' +
                                   'onerror="this.onerror=null; this.src=\'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjUwIiBoZWlnaHQ9IjUwIiBmaWxsPSIjRjVGNUY1Ii8+CjxwYXRoIGQ9Ik0yNSAyMEMyNi4xIDIwIDI3IDIwLjkgMjcgMjJDMjcgMjMuMSAyNi4xIDI0IDI1IDI0QzIzLjkgMjQgMjMgMjMuMSAyMyAyMkMyMyAyMC45IDIzLjkgMjAgMjUgMjBaTTE1IDEySDM1QzM2LjEgMTIgMzcgMTIuOSAzNyAxNFYzNkMzNyAzNy4xIDM2LjEgMzggMzUgMzhIMTVDMTMuOSAzOCAxMyAzNy4xIDEzIDM2VjE0QzEzIDEyLjkgMTMuOSAxMiAxNSAxMlpNMTUgMzZIMzVWMTRIMTVWMzZaTTE5IDI4TDIyIDMxTDI3IDI2TDMzIDMySDI3SDE3TDE5IDI4WiIgZmlsbD0iIzk5OTk5OSIvPgo8L3N2Zz4K\'; this.title=\'Image not found: ' + data + '\';">';
                        }
                        return '<div class="d-flex align-items-center justify-content-center bg-light" style="width:50px;height:50px;border:1px solid #ddd;"><small class="text-muted">No Image</small></div>';
                    }
                },
                {
                    data: 'action',
                    render: function(data, type, row) {
                        return '<a href="' + window.APP_CONFIG.masterItemUrl + '/view/' + row.kode + '" class="btn btn-primary btn-sm">View</a>';
                    }
                }
            ]
        });

        function formatRupiah(angka) {
            if (!angka) return 'Rp 0';

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

        $('.btn-get-data').click(function() {
            getData();
        });

        function getData() {
            $('#loading-filter').show();
            dataTable.clear().draw();

            var filter_kode = $('#filter-kode').val();
            var filter_nama = $('#filter-nama').val();
            var filter_harga_min = $('#filter-harga-min').val();
            var filter_harga_max = $('#filter-harga-max').val();

            $.ajax({
                url: window.APP_CONFIG.masterItemUrl + '/search',
                method: 'GET',
                dataType: 'json',
                data: {
                    kode: filter_kode,
                    nama: filter_nama,
                    hargamin: filter_harga_min,
                    hargamax: filter_harga_max
                },
                success: function(response) {
                    console.log('Response received:', response);

                    if (response.status === 200) {
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
                    console.error('Ajax Error:', xhr.responseText);
                    alert('Terjadi kesalahan server, tidak dapat mengambil data');
                    $('#loading-filter').hide();
                }
            });
        }

        function testImageAccess() {
            var testUrl = window.APP_CONFIG.imageUrl + '/test.jpg';
            var img = new Image();
            img.onload = function() {
                console.log('✓ Image access working: ' + testUrl);
            };
            img.onerror = function() {
                console.log('✗ Cannot access images at: ' + testUrl);
                console.log('Please check if folder exists: public/storage/foto_items/');
            };
            img.src = testUrl;
        }

        testImageAccess();
        getData();
    });
</script>
