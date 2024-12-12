@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/sertifikasi/create_ajax2') }}')" class="btn btn-sm btn-success mt-1"><i class="fa fa-plus">Tambah Data Sertifikasi</i></button>
        </div>
        </div>
<div class="card-body">
    <!-- Menampilkan Flash Message jika ada -->
    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <!-- Perulangan untuk setiap pelatihan -->
            @foreach ($sertifikasi as $item)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-1 font-weight-bold">{{ $item->nama_sertif }}</h5>
                    <p class="card-text text-muted mb-1">{{ $item->bidang->bidang_nama ?? 'Tanpa Bidang' }}</p>
                    <p class="card-text"><small class="text-secondary">Masa Berlaku: {{ $item->masa_berlaku ?? 'Tidak Terbatas' }}</small></p>
                </div>
            </div>
            @endforeach
            <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" data-width="75%" aria-hidden="true">
        </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-12">
            <!-- Tombol Tambah Sertifikasi -->
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('sertifikasi.dosen.create') }}" class="btn btn-warning shadow-sm">Tambah Data</a>
            </div>
        </div>
    </div> --}}
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
            ajax: "{{ route('pelatihan.list') }}", // URL ke method `list()` di controller
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_sertifikasi', name: 'nama_sertifikasi' },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'nama_bidang', name: 'nama_bidang' },
                { data: 'status', name: 'status' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
                // { data: 'download', name: 'download', orderable: false, searchable: false }, // Tambahkan kolom download
            ]
        });
    });
</script>
@endpush



   