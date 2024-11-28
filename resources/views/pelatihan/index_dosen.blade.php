@extends('layouts.template')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Perulangan untuk setiap pelatihan -->
            @foreach ($pelatihan as $item)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-1 font-weight-bold">{{ $item->nama_pelatihan }}</h5>
                    <p class="card-text text-muted mb-1">Bidang: {{ $item->bidang->bidang_nama ?? 'Tidak Diketahui' }}</p>
                    <p class="card-text text-muted mb-1">Penyelenggara: {{ $item->vendor->vendor_nama ?? 'Tidak Diketahui' }}</p>
                </div>
            </div>
            @endforeach

        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <!-- Tombol Tambah Pelatihan -->
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('pelatihan.dosen.create') }}" class="btn btn-warning shadow-sm">Tambah Data</a>
            </div>
        </div>
    </div>
</div>
@endsection
