@extends('layouts.template')

@section('content_header')
    <h1>Detail Bidang</h1>
@endsection

@section('content')
<div class="row">
    @foreach ($bidangs as $b)
        <div class="col-md-3">
            <div class="card bidang-card">
                <!-- Bagian gambar -->
                <img src="{{ $b->bidang_gambar ?? asset('default-image.png') }}" 
                     alt="{{ $b->bidang_nama ?? 'Gambar Tidak Ada' }}" 
                     class="card-img-top bidang-img">
                <!-- Bagian isi -->
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $b->bidang_nama ?? 'Nama Tidak Ada' }}</h5>
                    <a href="#" class="btn btn-primary">Detail Dosen</a>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Tombol Kembali -->
<div class="text-center mt-4">
    <a href="{{ route('dashboard') }}" class="btn btn-secondary kembali-btn">Kembali ke Dashboard</a>
</div>
@endsection

@section('css')
<style>
    /* Gaya untuk kartu bidang */
    .bidang-card {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
        background-color: #ffffff;
        overflow: hidden;
    }

    .bidang-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* Gambar dalam kartu */
    .bidang-img {
        height: 150px;
        object-fit: cover;
        border-bottom: 1px solid #ddd;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
    }

    .card-text {
        font-size: 14px;
        color: #666;
    }

    /* Tombol kembali */
    .kembali-btn {
        padding: 10px 20px;
        color: #ffffff;
        background-color: #6c757d;
        border-radius: 20px;
        text-decoration: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s;
    }

    .kembali-btn:hover {
        background-color: #5a6268;
    }
</style>
@endsection
