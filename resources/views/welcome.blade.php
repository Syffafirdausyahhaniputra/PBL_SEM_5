@extends('layouts.template')

@section('content')
    <!-- Data Section -->
    <div class="d-flex flex-column flex-lg-row">
        <!-- Chart Card -->
        <div class="card p-4 me-0 me-lg-4 mb-4 mb-lg-0" style="flex: 3; max-height: auto;">
            <h5 class="text-center fw-bold">Data Sertifikasi dan Pelatihan Dosen</h5>
            
            <div class="d-flex flex-column flex-lg-row">
                <div class="d-flex flex-column justify-content-around mb-3 mb-lg-0" style="flex: 1; margin-right: 10px;">
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
    </div>

    <!-- Bidang Section -->
    <h2 class="mt-5">Bidang</h2>

    <!-- Search Bar -->
    <div class="mb-2">
        <input type="text" id="searchInput" class="form-control" placeholder="Cari bidang..." oninput="filterBidang()">
    </div>

    <!-- Bidang Container -->
    <div class="bidang-container" id="bidangContainer">
        @foreach ($bidang as $b)
            <a href="{{ route('bidang.showDosenByBidang', ['id' => $b->bidang_id]) }}" class="text-decoration-none bidang-item">
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
            background-color: #f8f9fa;
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
            color: black;
            border: none;
            width: 150px;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
            transition: transform 0.3s;
        }

        .bidang-card:hover {
            transform: scale(1.05);
        }

        .bidang-card img {
            width: 80%;
            height: auto;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        @media (max-width: 576px) {
            .bidang-card {
                width: 120px;
                height: auto;
            }

            .bidang-card img {
                width: 70%;
            }
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
                datasets: [
                    {
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

        // Search Filter Function
        function filterBidang() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const bidangItems = document.querySelectorAll('.bidang-item');

            bidangItems.forEach(item => {
                const bidangName = item.textContent.toLowerCase();
                if (bidangName.includes(searchInput)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
@endsection
