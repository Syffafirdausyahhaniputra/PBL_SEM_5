@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Sertifikasi</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('/sertifikasi/export_excel') }}" class="btn btn-primary">
                    <i class="fa fa-file-excel"></i> Export Sertifikasi
                </a>
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/sertifikasi/export_pdf') }}" class="btn btn-warning">
                    <i class="fa fa-file-pdf"></i> Export Sertifikasi
                </a>
                <button onclick="modalAction('{{ url('/sertifikasi/import') }}')" class="btn btn-sm btn-info mt-1">
                    <i class="fa fa-upload"></i> Import Sertifikasi
                </button>
                <button onclick="modalAction('{{ url('/sertifikasi/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                    <i class="fa fa-plus"></i> Tambah Sertifikasi
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
                <table class="table table-bordered table-striped table-hover table-sm" id="table_sertifikasi">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sertifikasi</th>
                            <th>Tanggal</th>
                            <th>Periode</th>
                            <th>Tanggal Dibuat</th>
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

        var dataSertifikasi;
        $(document).ready(function() {
            // Inisialisasi DataTables
            dataSertifikasi = $('#table_sertifikasi').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('sertifikasi.list') }}", // Route untuk method list di controller
                    type: "GET",
                },
                columns: [
                    {
                        data: null,
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1; // Nomor urut otomatis
                        }
                    },
                    { data: "nama_sertif", className: "", orderable: true, searchable: true }, // Nama sertifikasi
                    { data: "tanggal", className: "", orderable: true, searchable: true },     // Nama dosen
                    { data: "periode", className: "", orderable: true, searchable: true }, // Periode
                    { data: "created_at", className: "text-center", orderable: true, searchable: true }, // Tanggal dibuat
                    {
                        data: null,
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-sm btn-warning" onclick="editAction(${data.data_sertifikasi_id})">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteAction(${data.data_sertifikasi_id})">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            `;
                        }
                    }
                ],
               
            });
        });

        // Fungsi untuk aksi edit
        function editAction(id) {
            modalAction(`{{ url('/sertifikasi/edit_ajax') }}/${id}`);
        }

        // Fungsi untuk aksi hapus
        function deleteAction(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: `{{ url('/sertifikasi/delete') }}/${id}`,
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        alert('Data berhasil dihapus!');
                        dataSertifikasi.ajax.reload(); // Reload DataTables
                    },
                    error: function() {
                        alert('Terjadi kesalahan, coba lagi!');
                    }
                });
            }
        }
    </script>
@endpush