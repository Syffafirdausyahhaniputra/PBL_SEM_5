@extends('layouts.template')

@section('title', $bidang->bidang_nama)

@section('content')
<div class="container mt-4">
    <!-- Card List Section -->
    <div class="card-list">
        @if ($bidang->dosenBidang->isNotEmpty())
            @foreach ($bidang->dosenBidang as $dosenBidang)
                <div class="card shadow-sm d-flex flex-row align-items-center p-3 mb-3 rounded">
                    {{-- Menampilkan Avatar --}}
                    <img 
                        src="{{ $dosenBidang->dosen2->user->avatar ? asset('avatars/'.$dosenBidang->dosen2->user->avatar) : asset('avatars/user.jpg') }}" 
                        alt="Foto {{ $dosenBidang->dosen2->user->nama ?? 'Dosen' }}" 
                        class="rounded-circle me-3"
                        style="width: 60px; height: 60px; object-fit: cover;"
                    >
                    {{-- Menampilkan Nama dan NIP --}}
                    <div>
                        <h5 class="mb-0">{{ $dosenBidang->dosen2->user->nama ?? 'Nama tidak ditemukan' }}</h5>
                        <small class="text-muted">NIP: {{ $dosenBidang->dosen2->user->nip ?? 'NIP tidak ditemukan' }}</small>
                    </div>
                </div>
            @endforeach
        @else
            <p class="no-data text-center">Tidak ada dosen yang terdaftar untuk bidang ini.</p>
        @endif
    </div>
</div>
@endsection




@section('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<style>
    /* Sidebar */
    .sidebar {
        background-color: #003366;
        color: white;
    }

    .sidebar a {
        color: white;
        font-size: 16px;
        font-weight: bold;
    }

    .sidebar a:hover {
        background-color: #004080;
    }

    .sidebar .active {
        background-color: #cb8587;
    }

    /* Header */
    .header {
        padding: 15px 20px;
        background-color: #f4f6f9;
        border-bottom: 1px solid #ddd;
    }

    .header h1 {
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }

    .header small {
        font-size: 14px;
        color: #888;
    }

    /* Card List */
    .card-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 20px;
    }

    .card {
        display: flex;
        align-items: center;
        gap: 15px;
        width: 100%;
        max-width: 400px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .card img {
        border-radius: 50%;
        width: 50px;
        height: 50px;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin: 0;
    }

    .card-subtitle {
        font-size: 14px;
        color: #666;
        margin: 0;
    }

    .no-data {
        font-size: 16px;
        color: #999;
        text-align: center;
        margin-top: 20px;
    }

    /* Responsiveness */
    @media (max-width: 768px) {
        .card-list {
            flex-direction: column;
        }
    }
</style>
@endsection
