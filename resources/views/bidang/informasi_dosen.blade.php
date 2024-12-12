@extends('layouts.template')

@section('content')
    <!-- Data and Profile Section -->
    <div class="d-flex flex-row">
        <!-- Chart Card -->
        <div class="card p-4 me-4" style="flex: 3; max-height: 300px; margin-right: 15px;">
            <div class="d-flex flex-column justify-content-around">
                <div class="text-center">
                    <h1 class="fw-bold">{{ $jumlahSertifikasiPelatihan }}</h1>
                    <h2>Sertifikasi dan Pelatihan</h2>
                </div>
            </div>
        </div>
        <!-- Profile Card -->
        <div class="card p-4 text-center d-flex align-items-center justify-content-center" style="flex: 1; color: black; max-height: 300px;">
            <h2 class="fw-bold">Profil</h2>
            <img src="{{ $dosen->dosen2?->user?->avatar ? asset('avatars/' . $dosen->dosen2->user->avatar) : asset('img/user.png') }}" 
            class="rounded-circle" width="100" height="100" alt="User Avatar">
            <h4 class="fw-bold">{{ $dosen->dosen2?->user?->nama ?? 'Nama tidak ditemukan' }}</h4>
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
                    <a href="{{ route('detail_sertif_dosen', ['id' => $item->sertif->bidang_id, 'id_dosen' => $dosen->dosen2->dosen_id]) }}" class="text-decoration-none sertifikasi-item">
                        <li class="list-group-item"> 
                            <strong>{{ $item->sertif->nama_sertif }}</strong>
                            <br>
                            Bidang: {{ $item->sertif->bidang->bidang_nama ?? 'N/A' }}
                            <br>
                            Masa Berlaku: {{ $item->sertif->masa_berlaku }}
                        </li>
                    </a> 
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
                    <li class="list-group-item">
                        <strong>{{ $item->pelatihan->nama_pelatihan }}</strong>
                        <br>
                        Bidang: {{ $item->pelatihan->bidang->bidang_nama ?? 'N/A' }}
                        <br>
                        Penyelenggara: {{ $item->pelatihan->vendor->vendor_nama ?? 'N/A' }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Styling untuk list sertifikasi dan pelatihan -->
    <style>
        /* Styling untuk card container */
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            background: linear-gradient(to bottom right, #ffffff, #f9f9f9);
        }

        /* Styling untuk list sertifikasi dan pelatihan */
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

        .list-group-item strong {
            color: #000000;
        }

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

        #sertifikasi-container, #pelatihan-container {
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
    </style>
@endsection
