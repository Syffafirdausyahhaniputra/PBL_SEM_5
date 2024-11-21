@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
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
