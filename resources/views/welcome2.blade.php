@extends('layouts.template')
@section('content')
        <!-- Data and Profile Section -->
        <div class="d-flex flex-row">
            <!-- Chart Card -->
            <div class="card p-4 me-4" style="flex: 3; max-height: 300px; margin-right: 15px;">
                <div class="d-flex flex-column justify-content-around" style="flex: 2; margin-right: 10px; margin-bottom: 50px;"">
                    <div class="d-flex flex-column justify-content-around" style="flex: 2; margin-right: 10px; margin-bottom: 50px;">
                        <div class="text-center">
                            <h1 class="fw-bold">{{ $jumlahSertifikasiPelatihan }}</h1>
                            <h2> Sertifikasi dan Pelatihan</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Profile Card -->
            <div class="card p-4 text-center d-flex align-items-center justify-content-center" style="flex: 1; color: black; max-height: 300px;">
                <h2 class="fw-bold">Profil</h2>
                <img src="{{ auth()->user()->avatar ? asset('avatars/' . auth()->user()->avatar) : asset('img/user.png') }}" class="rounded-circle" width="30" height="30" alt="User Avatar">
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
                    <a href="{{ route('sertifikasi.detail_sertif', ['id' => $item->sertif_id]) }}" class="text-decoration-none sertifikasi-item">
                        <li class="list-group-item"> 
                            <strong>{{ $item->nama_sertif }}</strong>
                            <br>
                            Bidang: {{ $item->bidang->bidang_nama ?? 'N/A' }}
                            <br>
                            Masa Berlaku: {{ $item->masa_berlaku }}
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
                    <a href="{{ route('pelatihan.detail_pelatihan', ['id' => $item->pelatihan_id]) }}" class="text-decoration-none pelatihan-item">
                        <li class="list-group-item">
                            <strong>{{ $item->nama_pelatihan }}</strong>
                            <br>
                            Bidang: {{ $item->bidang->bidang_nama ?? 'N/A' }}
                            <br>
                            Penyelenggara: {{ $item->vendor->vendor_nama }}
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
            border: 2px solid #007bff; /* Garis tepi biru */
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
    
        /* Highlight untuk teks */
        .list-group-item strong {
            color: #000000;
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
    
        /* Container layout */
        #sertifikasi-container, #pelatihan-container {
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
    </style>
    
    

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var sertifikasiData = @json($sertifikasi);
        var pelatihanData = @json($pelatihan);
        var ctx = document.getElementById('sertifikasiPelatihanChart').getContext('2d');
        var sertifikasiPelatihanChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Sertifikasi',
                        data: sertifikasiData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pelatihan',
                        data: pelatihanData,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection