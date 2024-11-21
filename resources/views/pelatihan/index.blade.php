@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" h href="{{ url('/pelatihan/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Pelatihan</a>
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/pelatihan/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Pelatihan</a>
                <button onclick="modalAction('{{ url('/pelatihan/import') }}')" class="btn btn-sm btn-info mt-1"><i class="fa fa-upload"> Import Pelatihan</i></button>
                <button onclick="modalAction('{{ url('/pelatihan/create_ajax') }}')" class="btn btn-sm btn-success mt-1"><i class="fa fa-plus"> Tambah Pelatihan</i></button>
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
        <table class="table table-bordered table-striped table-hover" id="table-pelatihan">
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
                @foreach ($pelatihan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_pelatihan }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->bidang->nama_bidang ?? '-' }}</td>
                    <td>
                    <a href="{{ route('pelatihan.show', $item->pelatihan_id) }}" class="btn btn-sm btn-primary">Show</a>
                        <a href="{{ route('pelatihan.edit', $item->pelatihan_id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('pelatihan.destroy', $item->pelatihan_id) }}" method="POST" style="display:inline;">
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
        $('#table-pelatihan').DataTable(); // Inisialisasi DataTables
    });
</script>
@endpush
