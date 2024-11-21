@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" h href="{{ url('/sertifikasi/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Sertifikasi</a>
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/pelatihan/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Sertifikasi</a>
                <button onclick="modalAction('{{ url('/sertifikasi/import') }}')" class="btn btn-sm btn-info mt-1"><i class="fa fa-upload"> Import Sertifikasi</i></button>
                <button onclick="modalAction('{{ url('/sertifikasi/create_ajax') }}')" class="btn btn-sm btn-success mt-1"><i class="fa fa-plus"> Tambah Sertifikasi</i></button>
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
        <table class="table table-bordered table-striped table-hover" id="table-sertifikasi">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelatihan</th>
                    <th>Tanggal</th>
                    <th>Bidang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sertifikasi as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_sertifikasi }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->bidang->nama_bidang ?? '-' }}</td>
                    <td>
                    <a href="{{ route('sertifikasi.show', $item->sertifikasi_id) }}" class="btn btn-sm btn-primary">Show</a>
                        <a href="{{ route('sertifikasi.edit', $item->sertifikasi_id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('sertifikasi.destroy', $item->sertifikasi_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#table-sertifikasi').DataTable(); // Inisialisasi DataTables
    });
</script>
@endpush
