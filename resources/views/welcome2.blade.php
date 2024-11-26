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
                <img src="{{ Auth::user()->avatar ? asset('storage/avatar/' . Auth::user()->avatar) : asset('img/user.png') }}"
                    class="rounded-circle mb-3" width="150" height="150" alt="User Avatar">
                <h4 class="fw-bold">{{ Auth::user()->nama }}</h4>
            </div>
        </div>
        
        <!-- Data Sertifikasi dan Pelatihan -->
        <div id="sertifikasi-container" class="mb-4">
        <h6 class="fw-bold">Sertifikasi</h6>
        @if ($sertifikasi->isEmpty())
            <p class="text-muted">Belum ada data sertifikasi.</p>
        @else
            <ul class="list-group">
                @foreach ($sertifikasi as $item)
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


                <!-- Pelatihan Section -->
                <div id="pelatihan-container">
        <h6 class="fw-bold">Pelatihan</h6>
        @if ($pelatihan->isEmpty())
            <p class="text-muted">Belum ada data pelatihan.</p>
        @else
            <ul class="list-group">
                @foreach ($pelatihan as $item)
                    <li class="list-group-item">
                        <strong>{{ $item->nama_pelatihan }}</strong>
                        <br>
                        Bidang: {{ $item->bidang->bidang_nama ?? 'N/A' }}
                        <br>
                        Lokasi: {{ $item->lokasi }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>


    <!-- Styling untuk list sertifikasi dan pelatihan -->
    <style>
        .list-group-item {
            border: 1px solid #ddd;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .list-group-item strong {
            color: #007bff;
        }
    </style>
    
    <!-- JavaScript Section -->
    @push('js')
    <script>
        $(document).ready(function() {
            // Ajax untuk riwayat jika diperlukan
        });
    </script>
    

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
    @endpush
@endsection