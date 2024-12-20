@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group row">
                        <label for="filter-keterangan" class="col-4 control-label col-form-label">Filter:</label>
                        <div class="col-7">
                            <select id="filter-keterangan" class="form-control">
                                <option value="">Semua</option>
                                <option value="Penunjukan">Penunjukan</option>
                                <option value="Menunggu Validasi">Menunggu Validasi</option>
                                <option value="Validasi Disetujui">Validasi Disetujui</option>
                                <option value="Validasi Ditolak">Validasi Ditolak</option>
                                <option value="Mandiri">Mandiri</option>
                                <option value="Sertifikasi Selesai">Sertifikasi Selesai</option>
                                <option value="Pelatihan Selesai">Pelatihan Selesai</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_validasi">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Type</th>
                            <th>Nama</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" jenis="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var datajenis;

        $(document).ready(function() {
            // Initialize DataTable
            datajenis = $('#table_validasi').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('validasi/list') }}",
                    dataType: "json",
                    type: "POST",
                    data: function(d) {
                        d.keterangan = $('#filter-keterangan').val(); // Add filter parameter
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "type",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "keterangan",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "status",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Event listener for filter dropdown
            $('#filter-keterangan').change(function() {
                datajenis.ajax.reload(); // Reload DataTable with new filter value
            });
        });
    </script>
@endpush
