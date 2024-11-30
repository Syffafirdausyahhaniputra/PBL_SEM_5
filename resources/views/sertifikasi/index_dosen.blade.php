@extends('layouts.template')

@section('content')
<div class="container">
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
            <!-- Perulangan untuk setiap sertifikasi -->
            @foreach ($sertifikasi as $item)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-1 font-weight-bold">{{ $item->nama_sertif }}</h5>
                    <p class="card-text text-muted mb-1">{{ $item->bidang->bidang_nama ?? 'Tanpa Bidang' }}</p>
                    <p class="card-text"><small class="text-secondary">Masa Berlaku: {{ $item->masa_berlaku ?? 'Tidak Terbatas' }}</small></p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Tombol Tambah Sertifikasi -->
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('sertifikasi.dosen.create') }}" class="btn btn-warning shadow-sm">Tambah Data</a>
            </div>
        </div>
    </div>
</div>
@endsection