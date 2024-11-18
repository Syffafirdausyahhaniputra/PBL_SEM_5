@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Pelatihan</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('/pelatihan/export_excel') }}" class="btn btn-primary">
                    <i class="fa fa-file-excel"></i> Export Pelatihan
                </a>
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/pelatihan/export_pdf') }}" class="btn btn-warning">
                    <i class="fa fa-file-pdf"></i> Export Pelatihan
                </a>
                <button onclick="modalAction('{{ url('/pelatihan/import') }}')" class="btn btn-sm btn-info mt-1">
                    <i class="fa fa-upload"></i> Import Pelatihan
                </button>
                <button onclick="modalAction('{{ url('/pelatihan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                    <i class="fa fa-plus"></i> Tambah Pelatihan
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Notifikasi sukses -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Notifikasi error -->
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_pelatihan">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pelatihan</th>
                            <th>Jenis</th>
                            <th>Vendor</th>
                            <th>Tanggal</th>
                            <th>Periode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal untuk form -->
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
<!-- Tambahkan custom CSS jika diperlukan -->
@endpush

@push('js')
    <script>
        // Fungsi untuk memunculkan modal dengan konten dari URL
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataPelatihan;
        $(document).ready(function() {
            // Inisialisasi DataTables
            dataPelatihan = $('#table_pelatihan').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    "url": "{{ url('pelatihan/list') }}",
                    "type": "POST",
                    "dataType": "json",
                    "data": {
                        "_token": "{{ csrf_token() }}" // Token CSRF untuk keamanan
                    }
                },
                columns: [
                    {
                        // Nomor urut otomatis dari Laravel DataTables
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        // Nama pelatihan
                        data: "nama_pelatihan",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        // Jenis pelatihan
                        data: "jenis_pelatihan",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        // Vendor pelatihan
                        data: "vendor",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        // Tanggal pelatihan
                        data: "tanggal",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        // Periode pelatihan
                        data: "periode",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        // Tombol aksi
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });
    </script>
@endpush
