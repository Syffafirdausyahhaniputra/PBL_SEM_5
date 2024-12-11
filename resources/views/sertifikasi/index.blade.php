@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/sertifikasi/create_ajax') }}')" class="btn btn-sm btn-success mt-1"><i class="fa fa-plus"> Tambah Data Sertifikasi</i></button>
                <button onclick="modalAction('{{ url('/sertifikasi/tunjuk') }}')" class="btn btn-sm btn-success mt-1"><i class="fa fa-plus"> Tambah Penunjukkan Sertifikasi</i></button>
        </div>
        <div class="card-body">
            <!-- Pesan sukses/gagal -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <!-- Tabel Sertifikasi -->
            <table class="table table-bordered table-striped table-hover" id="table-sertifikasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Sertifikasi</th>
                        <th>Tanggal</th>
                        <th>Bidang</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
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
        $('#table-sertifikasi').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('sertifikasi.list') }}", // URL ke method `list()` di controller
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_sertif', name: 'nama_sertif' },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'nama_bidang', name: 'pnama_bidang' },
                { data: 'status', name: 'status' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush
