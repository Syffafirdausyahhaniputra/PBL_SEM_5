@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="card-title">Data Pelatihan</h3>
            <div class="card-tools d-flex gap-3 flex-wrap" style="margin-left: auto;">
                <button onclick="modalAction('{{ url('/pelatihan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                    <i class="fa fa-plus"></i> Tambah Data Pelatihan
                </button>
                <div style="width: 10px;"></div>
                <button onclick="modalAction('{{ url('/pelatihan/tunjuk') }}')" class="btn btn-sm btn-success mt-1">
                    <i class="fa fa-plus"></i> Tambah Penunjukkan Pelatihan
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Pesan sukses/gagal -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <!-- Tabel Pelatihan -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover w-100" id="table-pelatihan">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pelatihan</th>
                            <th>Tanggal</th>
                            <th>Bidang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true">
    </div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        $(document).ready(function() {
            $('#table-pelatihan').DataTable({
                processing: true,
                serverSide: true,
                searching: false, // Menonaktifkan fitur search
                ajax: "{{ route('pelatihan.list') }}", // URL ke method `list()` di controller
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_pelatihan',
                        name: 'nama_pelatihan'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'nama_bidang',
                        name: 'nama_bidang'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ],
                responsive: true,
                autoWidth: false
            });
        });
    </script>
@endpush
