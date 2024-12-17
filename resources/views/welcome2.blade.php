@extends('layouts.template')
@section('content')
<!-- Data and Profile Section -->
<div class="d-flex flex-wrap flex-lg-nowrap mb-4">
    <!-- Data Sertifikasi dan Pelatihan -->
    <div class="card p-4 me-lg-4 mb-4 mb-lg-0 d-flex justify-content-center align-items-center flex-fill" style="max-height: 300px;">
        <div class="text-center">
            <h1 class="fw-bold">{{ $jumlahSertifikasiPelatihan }}</h1>
            <h2>Sertifikasi dan Pelatihan</h2>
        </div>
    </div>            
    <!-- Profile Card -->
    <div class="card p-4 text-center d-flex align-items-center justify-content-center flex-fill" style="max-height: 300px;">
        <h2 class="fw-bold">Profil</h2>
        <img src="{{ auth()->user()->avatar ? asset('avatars/' . auth()->user()->avatar) : asset('img/user.png') }}" 
             class="rounded-circle mb-3" width="80" height="80" alt="User Avatar">
        <h4 class="fw-bold">{{ Auth::user()->nama }}</h4>
    </div>
</div>

<!-- Data Sertifikasi -->
<div id="sertifikasi-container" class="mb-4 card">
    <h6 class="fw-bold">Sertifikasi</h6>
    @if ($sertifikasi->isEmpty())
        <p class="text-muted">Belum ada data sertifikasi.</p>
    @else
        <ul class="list-group">
            @foreach ($sertifikasi as $item)
            <a href="{{ route('sertifikasi.detail_sertif', ['id' => $item->sertif->sertif_id]) }}" class="text-decoration-none sertifikasi-item">
                <li class="list-group-item"> 
                    <strong>{{ $item->sertif->nama_sertif }}</strong>
                    <br>
                    Bidang: {{ $item->sertif->bidang->bidang_nama ?? 'N/A' }}
                    <br>
                    Masa Berlaku: {{ $item->sertif->masa_berlaku }}
                </li>
            @endforeach
        </ul>
    @endif
</div>

<!-- Data Pelatihan -->
<div id="pelatihan-container" class="card">
    <h6 class="fw-bold">Pelatihan</h6>
    @if ($pelatihan->isEmpty())
        <p class="text-muted">Belum ada data pelatihan.</p>
    @else
        <ul class="list-group">
            @foreach ($pelatihan as $item)
            <a href="{{ route('pelatihan.detail_pelatihan', ['id' => $item->pelatihan->pelatihan_id]) }}" class="text-decoration-none pelatihan-item">
                <li class="list-group-item">
                    <strong>{{ $item->pelatihan->nama_pelatihan }}</strong>
                    <br>
                    Bidang: {{ $item->pelatihan->bidang->bidang_nama ?? 'N/A' }}
                    <br>
                    Penyelenggara: {{ $item->pelatihan->vendor->vendor_nama }}
                </li>
            @endforeach
        </ul>
    @endif
</div>

<!-- Styling -->
<style>
    /* Styling untuk container data dan profil */
    .d-flex.flex-wrap {
        gap: 15px;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        background: linear-gradient(to bottom right, #ffffff, #f9f9f9);
    }

    /* Styling gambar profil */
    img.rounded-circle {
        object-fit: cover;
        border: 2px solid #007bff;
    }

    /* List group styling */
    .list-group-item {
        border: 2px solid #007bff;
        margin-bottom: 10px;
        border-radius: 8px;
        background: #fdfdfd;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .list-group-item:hover {
        transform: translateY(-3px);
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Header section */
    h1, h2, h6 {
        font-family: 'Poppins', sans-serif;
        margin-bottom: 15px;
    }

    h1 {
        font-size: 2.5rem;
        color: #000000;
    }

    h2, h6 {
        color: #333;
    }

    /* Sertifikasi dan pelatihan container styling */
    #sertifikasi-container, #pelatihan-container {
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    /* Responsiveness */
    @media (max-width: 768px) {
        .d-flex.flex-wrap {
            flex-direction: column;
        }

        .card {
            max-height: none;
        }
    }
</style>
@endsection
