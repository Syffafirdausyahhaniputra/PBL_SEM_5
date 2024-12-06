@extends('layouts.template')

@section('content')
    <!-- Data and Profile Section -->
    <div class="d-flex flex-row">
        <!-- Chart Card -->
        <div class="card p-4 me-4" style="flex: 3; max-height: 300px; margin-right: 15px;">
            <h5 class="text-center fw-bold">Data Sertifikasi dan Pelatihan Dosen</h5>
            
            <div class="d-flex">
                <div class="d-flex flex-column justify-content-around" style="flex: 1; margin-right: 10px;">
                    <div class="text-center">
                        <h4 class="fw-bold">{{ $jumlahSertifikasiPelatihan }}</h4>
                        <p>Jumlah Sertifikasi dan Pelatihan</p>
                    </div>
                    <div class="text-center">
                        <h4 class="fw-bold">{{ $rataRataSertifikasiPelatihanPerPeriode }}</h4>
                        <p>Rata-rata Sertifikasi dan Pelatihan per-periode</p>
                    </div>
                </div>

                <div style="flex: 2;">
                    <canvas id="sertifikasiPelatihanChart" style="height: 200px; max-height: 200px;"></canvas>
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

    <!-- Bidang Section -->
    <h2 class="mt-5">Bidang</h2>
    <div class="bidang-container">
        @foreach ($bidang as $b)
            <a href="{{ route('bidang.show_dosen', $b->id) }}" class="text-decoration-none">
                <div class="bidang-card">
                    <img src="{{ asset('img/IT.png') }}" alt="{{ $b->bidang_nama }}">
                    <h5 class="card-title text-center">{{ $b->bidang_nama }}</h5>
                </div>
            </a>
        @endforeach
    </div>


    <!-- CSS Styles -->
    <style>
        .bidang-container {
            background-color: ;
            padding: 20px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            flex-wrap: wrap;
            gap: 20px;
        }

        .bidang-card {
            background-color: white;
            border: none;
            width: 150px;
            height: 150px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .bidang-card img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 10px;
        }

    </style>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var labels = @json($labels);
        var sertifikasiData = @json($sertifikasiData);
        var pelatihanData = @json($pelatihanData);

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
