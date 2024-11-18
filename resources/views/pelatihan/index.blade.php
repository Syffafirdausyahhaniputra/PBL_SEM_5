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
                            <th>Nama Dosen</th>
                            <th>Status</th>
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

        var dataPelatihan;
        $(document).ready(function() {
            // Inisialisasi DataTables
            dataPelatihan = $('#table_pelatihan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pelatihan.list') }}", // Route untuk method list di controller
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
                    { data: "nama_pelatihan", className: "", orderable: true, searchable: true }, // Nama pelatihan
                    { data: "nama_dosen", className: "", orderable: true, searchable: true },     // Nama dosen
                    { data: "status", className: "text-center", orderable: true, searchable: true }, // Status
                    { data: "created_at", className: "text-center", orderable: true, searchable: true }, // Tanggal dibuat
                    {
                        data: null,
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-sm btn-warning" onclick="editAction(${data.data_pelatihan_id})">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteAction(${data.data_pelatihan_id})">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            `;
                        }
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });

        // Fungsi untuk aksi edit
        function editAction(id) {
            modalAction(`{{ url('/pelatihan/edit_ajax') }}/${id}`);
        }

        // Fungsi untuk aksi hapus
        function deleteAction(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: `{{ url('/pelatihan/delete') }}/${id}`,
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        alert('Data berhasil dihapus!');
                        dataPelatihan.ajax.reload(); // Reload DataTables
                    },
                    error: function() {
                        alert('Terjadi kesalahan, coba lagi!');
                    }
                });
            }
        }
    </script>
@endpush
